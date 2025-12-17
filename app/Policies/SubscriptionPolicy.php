<?php

namespace App\Policies;

use App\Models\Subscription;
use App\Models\User;

class SubscriptionPolicy
{
    /**
     * Determine if user can view subscription
     */
    public function view(User $user, Subscription $subscription): bool
    {
        return $user->id === $subscription->user_id;
    }

    /**
     * Determine if user can update subscription
     */
    public function update(User $user, Subscription $subscription): bool
    {
        return $user->id === $subscription->user_id;
    }
}