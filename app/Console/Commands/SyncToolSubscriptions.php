<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Models\Tool;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncToolSubscriptions extends Command
{
    protected $signature = 'tools:sync-subscriptions';

    protected $description = 'Fetch and sync subscriptions from all connected tools into the Hub database';

    public function handle(): int
    {
        $tools = Tool::where('status', true)
            ->where('is_connected', true)
            ->whereNotNull('api_url')
            ->get();

        if ($tools->isEmpty()) {
            $this->warn('No connected tools found.');
            return self::SUCCESS;
        }

        $this->info("Syncing subscriptions from {$tools->count()} tool(s)...");

        $totalSynced = 0;

        foreach ($tools as $tool) {
            $this->line("  → Fetching from: {$tool->name} ({$tool->api_url})");

            $subscriptions = $tool->fetchSubscriptions();

            if (empty($subscriptions)) {
                $this->warn("    No subscriptions returned from {$tool->name}.");
                continue;
            }

            $synced = 0;

            foreach ($subscriptions as $data) {
                try {
                    // Look up by subdomain first — it may already exist as a Hub subscription
                    Subscription::updateOrCreate(
                        [
                            'subdomain' => $data['subdomain'] ?? $data['tenant_id'],
                        ],
                        [
                            'tool_id' => $tool->id,
                            'is_external' => true,
                            'external_subscription_id' => $data['tenant_id'],
                            'external_user_id' => $data['platform_user_id'] ?? null,
                            'external_package_name' => $data['package_name'] ?? null,
                            'admin_email' => $data['admin_email'] ?? null,
                            'status' => $this->mapStatus($data['status'] ?? 'inactive'),
                            'starts_at' => $data['starts_at'] ?? now(),
                            'expires_at' => $data['expires_at'] ?? null,
                        ]
                    );
                    $synced++;
                } catch (\Exception $e) {
                    $this->error("    Failed to sync tenant {$data['tenant_id']}: " . $e->getMessage());
                    Log::error('SyncToolSubscriptions: failed to sync tenant', [
                        'tool' => $tool->name,
                        'tenant_id' => $data['tenant_id'] ?? null,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $this->info("    ✓ Synced {$synced} subscription(s) from {$tool->name}.");
            $totalSynced += $synced;
        }

        $this->info("Done. Total synced: {$totalSynced}");

        return self::SUCCESS;
    }

    /**
     * Map ideenpipeline statuses to cip-tools subscription statuses
     */
    private function mapStatus(string $status): string
    {
        return match ($status) {
            'active' => 'active',
            'inactive' => 'expired',
            'suspended' => 'cancelled',
            default => 'expired',
        };
    }
}
