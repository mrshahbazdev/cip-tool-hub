<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\Subscription;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    private ?string $plainPassword = null;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Mutate form data before saving
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Store plain password before it gets hashed
        if (isset($data['password']) && filled($data['password'])) {
            $this->plainPassword = $data['password'];
            
            Log::info('Password change detected in Filament', [
                'user_id' => $this->record->id,
                'email' => $this->record->email,
            ]);
        }

        return $data;
    }

    /**
     * After save - sync password to tenants
     */
    protected function afterSave(): void
    {
        if ($this->plainPassword) {
            Log::info('Starting password sync after save', [
                'user_id' => $this->record->id,
                'email' => $this->record->email,
            ]);

            $synced = $this->syncPasswordToTenants($this->record, $this->plainPassword);

            if ($synced > 0) {
                Notification::make()
                    ->success()
                    ->title('Password Updated & Synced!')
                    ->body("Password changed and synced to {$synced} tenant(s)")
                    ->persistent()
                    ->send();
                
                Log::info('Password sync successful', [
                    'synced_count' => $synced,
                ]);
            } else {
                Notification::make()
                    ->info()
                    ->title('Password Updated')
                    ->body('Password updated in main platform (no active tenants to sync)')
                    ->send();
                
                Log::info('No tenants to sync', [
                    'user_id' => $this->record->id,
                ]);
            }

            // Clear temporary password
            $this->plainPassword = null;
        }
    }

    /**
     * Sync password to all user's tenants
     */
    protected function syncPasswordToTenants($user, string $newPassword): int
    {
        $subscriptions = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('is_tenant_active', true)
            ->whereNotNull('tenant_id')
            ->with('package.tool')
            ->get();

        if ($subscriptions->isEmpty()) {
            Log::info('No active tenants found', [
                'user_id' => $user->id,
            ]);
            return 0;
        }

        Log::info('Syncing password to tenants', [
            'user_id' => $user->id,
            'email' => $user->email,
            'subscriptions_count' => $subscriptions->count(),
        ]);

        $syncCount = 0;

        foreach ($subscriptions as $subscription) {
            $tool = $subscription->package->tool;

            if (!$tool || !$tool->is_connected) {
                Log::warning('Tool not connected, skipping', [
                    'tool_id' => $tool->id ?? 'N/A',
                    'tenant_id' => $subscription->tenant_id,
                ]);
                continue;
            }

            try {
                $apiUrl = $tool->api_url . '/api/' . $subscription->tenant_id . '/update-password';
                
                Log::info('Sending password sync request', [
                    'tenant_id' => $subscription->tenant_id,
                    'email' => $user->email,
                    'url' => $apiUrl,
                ]);

                $response = Http::timeout(10)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $tool->api_token,
                        'Accept' => 'application/json',
                    ])
                    ->post($apiUrl, [
                        'email' => $user->email,
                        'password' => $newPassword,
                    ]);

                if ($response->successful()) {
                    $syncCount++;
                    Log::info('Password synced successfully', [
                        'tenant_id' => $subscription->tenant_id,
                        'tool' => $tool->name,
                    ]);
                } else {
                    Log::error('Password sync failed', [
                        'tenant_id' => $subscription->tenant_id,
                        'status' => $response->status(),
                        'response' => $response->body(),
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Password sync exception', [
                    'tenant_id' => $subscription->tenant_id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }

        Log::info('Password sync completed', [
            'synced' => $syncCount,
            'total' => $subscriptions->count(),
        ]);

        return $syncCount;
    }
}