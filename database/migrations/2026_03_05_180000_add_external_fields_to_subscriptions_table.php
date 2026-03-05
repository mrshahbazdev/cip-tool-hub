<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Make user_id and package_id nullable for external subscriptions
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->unsignedBigInteger('package_id')->nullable()->change();

            // Track which tool this subscription came from
            $table->unsignedBigInteger('tool_id')->nullable()->after('user_id');

            // External subscription tracking fields
            $table->boolean('is_external')->default(false)->after('tool_id');
            $table->string('external_subscription_id')->nullable()->after('is_external');
            $table->string('external_user_id')->nullable()->after('external_subscription_id');
            $table->string('external_package_name')->nullable()->after('external_user_id');

            // Index for fast lookup
            $table->index('tool_id');
            $table->index('is_external');
            $table->index('external_subscription_id');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropIndex(['tool_id']);
            $table->dropIndex(['is_external']);
            $table->dropIndex(['external_subscription_id']);
            $table->dropColumn(['tool_id', 'is_external', 'external_subscription_id', 'external_user_id', 'external_package_name']);
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->unsignedBigInteger('package_id')->nullable(false)->change();
        });
    }
};
