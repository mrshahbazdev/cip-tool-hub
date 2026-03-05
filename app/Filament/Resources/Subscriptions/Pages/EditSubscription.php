<?php

namespace App\Filament\Resources\Subscriptions\Pages;

use App\Filament\Resources\Subscriptions\SubscriptionResource;
use App\Models\Subscription;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EditSubscription extends EditRecord
{
    protected static string $resource = SubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    /**
     * After saving, push status and expiry changes back to the external tool's API
     */
    protected function afterSave(): void
    {
        /** @var Subscription $record */
        $record = $this->record;

        // Only push to external tool if this subscription has an external_subscription_id
        if (!$record->external_subscription_id || !$record->tool) {
            return;
        }

        $tool = $record->tool;

        if (!$tool->api_url || !$tool->api_token) {
            return;
        }

        // Map cip-tools status to ideenpipeline status
        $toolStatus = match ($record->status) {
            'active' => 'active',
            'expired' => 'inactive',
            'cancelled' => 'suspended',
            default => 'inactive',
        };

        try {
            $payload = ['status' => $toolStatus];

            if ($record->expires_at) {
                $payload['expires_at'] = $record->expires_at->toIso8601String();
            }

            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $tool->api_token,
                    'Accept' => 'application/json',
                ])
                ->post(
                    $tool->api_url . '/api/tenants/' . $record->external_subscription_id . '/update-status',
                    $payload
                );

            if ($response->successful()) {
                Notification::make()
                    ->title('Tool Updated')
                    ->body("Status pushed to {$tool->name} successfully.")
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Tool Update Warning')
                    ->body("Saved locally but could not push to {$tool->name}: " . $response->status())
                    ->warning()
                    ->send();
            }
        } catch (\Exception $e) {
            Log::error('Failed to push subscription status to tool', [
                'tool' => $tool->name,
                'subscription_id' => $record->external_subscription_id,
                'error' => $e->getMessage(),
            ]);

            Notification::make()
                ->title('Tool Update Failed')
                ->body("Saved locally but failed to push to {$tool->name}.")
                ->danger()
                ->send();
        }
    }
}
