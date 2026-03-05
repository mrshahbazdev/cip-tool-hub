<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'transaction_id',
        'subdomain',
        'starts_at',
        'expires_at',
        'status',
        // External tool subscription fields
        'tool_id',
        'is_external',
        'external_subscription_id',
        'external_user_id',
        'external_package_name',
        'admin_email',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_external' => 'boolean',
    ];

    /**
     * Get full domain attribute
     */
    public function getFullDomainAttribute(): string
    {
        if ($this->package && $this->package->tool) {
            return $this->subdomain . '.' . $this->package->tool->domain;
        }

        if ($this->tool) {
            return $this->subdomain . '.' . $this->tool->domain;
        }

        return $this->subdomain;
    }

    /**
     * Subscription belongs to user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Subscription belongs to package
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Subscription belongs to transaction
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Subscription belongs to a tool (for external subscriptions)
     */
    public function tool(): BelongsTo
    {
        return $this->belongsTo(Tool::class);
    }

    /**
     * Check if subscription is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' &&
            ($this->expires_at === null || $this->expires_at > now());
    }

    /**
     * Check if subscription is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at <= now();
    }

    /**
     * Get days remaining
     */
    public function daysRemaining(): int
    {
        if ($this->expires_at === null || $this->isExpired()) {
            return 0;
        }

        return now()->diffInDays($this->expires_at);
    }
}