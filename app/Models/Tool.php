<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class Tool extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'domain',
        'logo',
        'description',
        'status',
        'api_url',
        'api_token',
        'webhook_url',
        'is_connected',
        'last_ping_at',
        'connection_metadata',
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_connected' => 'boolean',
        'last_ping_at' => 'datetime',
        'connection_metadata' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tool) {
            if (empty($tool->api_token)) {
                $tool->api_token = Str::random(64);
            }
        });
    }

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class);
    }

    /**
     * Check connection to tool server
     */
    public function checkConnection(): bool
    {
        if (!$this->api_url) {
            return false;
        }

        try {
            $response = Http::timeout(5)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->api_token,
                    'Accept' => 'application/json',
                ])
                ->get($this->api_url . '/api/health');

            $isConnected = $response->successful();

            $this->update([
                'is_connected' => $isConnected,
                'last_ping_at' => now(),
                'connection_metadata' => [
                    'status_code' => $response->status(),
                    'last_check' => now()->toIso8601String(),
                    'response' => $response->json(),
                ],
            ]);

            return $isConnected;
        } catch (\Exception $e) {
            $this->update([
                'is_connected' => false,
                'last_ping_at' => now(),
                'connection_metadata' => [
                    'error' => $e->getMessage(),
                    'last_check' => now()->toIso8601String(),
                ],
            ]);

            return false;
        }
    }

    /**
     * Create tenant on tool server
     */
    public function createTenant(array $data): array
    {
        if (!$this->api_url || !$this->is_connected) {
            throw new \Exception('Tool is not connected');
        }

        $response = Http::timeout(30)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->api_token,
                'Accept' => 'application/json',
            ])
            ->post($this->api_url . '/api/tenants/create', $data);

        if (!$response->successful()) {
            throw new \Exception('Failed to create tenant: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Update tenant status
     */
    public function updateTenantStatus(string $tenantId, string $status): array
    {
        $response = Http::timeout(10)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->api_token,
                'Accept' => 'application/json',
            ])
            ->patch($this->api_url . '/api/tenants/' . $tenantId . '/update', [
                'status' => $status,
            ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to update tenant');
        }

        return $response->json();
    }

    /**
     * Delete tenant
     */
    public function deleteTenant(string $tenantId): bool
    {
        $response = Http::timeout(10)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->api_token,
                'Accept' => 'application/json',
            ])
            ->delete($this->api_url . '/api/tenants/' . $tenantId);

        return $response->successful();
    }
}