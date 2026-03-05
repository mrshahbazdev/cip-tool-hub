<?php

namespace App\Policies;

use App\Models\Subscription;
use App\Models\User;

class SubscriptionPolicy
{
    /**
     * Determine if user can view subscription.
     * Admins can view all; regular users only their own.
     */
    public function view(User $user, Subscription $subscription): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->id === $subscription->user_id;
    }

    /**
     * Determine if user can update subscription.
     * Admins can update all; regular users only their own.
     */
    public function update(User $user, Subscription $subscription): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->id === $subscription->user_id;
    }

    /**
     * Determine if user can delete subscription.
     * Only admins can delete.
     */
    public function delete(User $user, Subscription $subscription): bool
    {
        return $user->role === 'admin';
    }
}