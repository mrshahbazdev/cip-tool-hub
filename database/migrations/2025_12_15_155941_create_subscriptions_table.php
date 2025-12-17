<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('package_id');
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->string('subdomain')->unique();
            $table->timestamp('starts_at');
            $table->timestamp('expires_at')->nullable();
            $table->string('status', 20)->default('pending');
            
            // Tenant info
            $table->string('tenant_id')->nullable()->unique();
            $table->string('tenant_database')->nullable();
            $table->boolean('is_tenant_active')->default(false);
            $table->timestamp('tenant_created_at')->nullable();
            $table->string('admin_email')->nullable();
            
            $table->timestamps();
            
            // Indexes only (no foreign keys yet)
            $table->index('user_id');
            $table->index('package_id');
            $table->index('transaction_id');
            $table->index('status');
            $table->index('tenant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};