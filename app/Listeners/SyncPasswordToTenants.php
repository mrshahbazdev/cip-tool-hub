<?php

namespace App\Listeners;

use App\Events\UserPasswordChanged;
use App\Models\Subscription;
use App\Models\Tool;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncPasswordToTenants
{
    /**
     * Handle the event
     */
    public function handle(UserPasswordChanged $event): void
    {
        $user = $event->user;
        $newPassword = $event->newPassword;

        // Get all active subscriptions for this user
        $subscriptions = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('is_tenant_active', true)
            ->whereNotNull('tenant_id')
            ->with('package.tool')
            ->get();

        Log::info('Syncing password to tenants', [
            'user_id' => $user->id,
            'email' => $user->email,
            'subscriptions_count' => $subscriptions->count(),
        ]);

        foreach ($subscriptions as $subscription) {
            try {
                $this->syncPasswordToTenant($subscription, $newPassword);
            } catch (\Exception $e) {
                Log::error('Failed to sync password to tenant', [
                    'subscription_id' => $subscription->id,
                    'tenant_id' => $subscription->tenant_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Sync password to specific tenant
     */
    private function syncPasswordToTenant(Subscription $subscription, string $newPassword): void
    {
        $tool = $subscription->package->tool;

        if (!$tool->is_connected) {
            Log::warning('Tool not connected, skipping password sync', [
                'tool_id' => $tool->id,
                'tenant_id' => $subscription->tenant_id,
            ]);
            return;
        }

        // Call tool server API to update password
        $response = Http::timeout(10)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $tool->api_token,
                'Accept' => 'application/json',
            ])
            ->post($tool->api_url . '/api/tenants/' . $subscription->tenant_id . '/update-password', [
                'email' => $subscription->user->email,
                'password' => $newPassword,
            ]);

        if ($response->successful()) {
            Log::info('Password synced to tenant', [
                'tenant_id' => $subscription->tenant_id,
                'email' => $subscription->user->email,
            ]);
        } else {
            Log::error('Password sync failed', [
                'tenant_id' => $subscription->tenant_id,
                'status_code' => $response->status(),
                'response' => $response->body(),
            ]);
        }
    }
}