<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->string('api_url')->nullable()->after('description');
            $table->string('api_token', 64)->unique()->nullable()->after('api_url');
            $table->string('webhook_url')->nullable()->after('api_token');
            $table->boolean('is_connected')->default(false)->after('webhook_url');
            $table->timestamp('last_ping_at')->nullable()->after('is_connected');
            $table->json('connection_metadata')->nullable()->after('last_ping_at');
        });
    }

    public function down(): void
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->dropColumn([
                'api_url',
                'api_token',
                'webhook_url',
                'is_connected',
                'last_ping_at',
                'connection_metadata'
            ]);
        });
    }
};