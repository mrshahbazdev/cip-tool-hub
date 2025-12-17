<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'tool_id',
        'name',
        'price',
        'duration_type',
        'duration_value',
        'features',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_value' => 'integer',
        'status' => 'boolean',
        'features' => 'array',
    ];

    /**
     * Package belongs to tool
     */
    public function tool(): BelongsTo
    {
        return $this->belongsTo(Tool::class);
    }

    /**
     * Package has many subscriptions
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Package has many transactions
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return '€' . number_format($this->price, 2);
    }

    /**
     * Get duration text
     */
    public function getDurationTextAttribute(): string
    {
        if ($this->duration_type === 'lifetime') {
            return 'Lifetime';
        }

        $value = $this->duration_value;
        $type = $this->duration_type;

        return $value . ' ' . ucfirst($type);
    }
}