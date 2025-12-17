<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // For SQLite, we need to recreate the table
        if (DB::getDriverName() === 'sqlite') {
            // Get all existing data
            $tools = DB::table('tools')->get();
            
            // Drop and recreate table
            Schema::dropIfExists('tools');
            
            Schema::create('tools', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('domain')->unique();
                $table->string('logo')->nullable();
                $table->text('description')->nullable();
                $table->string('api_url')->nullable();
                $table->string('api_token', 64)->unique()->nullable();
                $table->string('webhook_url')->nullable();
                $table->boolean('is_connected')->default(false);
                $table->timestamp('last_ping_at')->nullable();
                $table->json('connection_metadata')->nullable();
                $table->boolean('status')->default(true);
                $table->timestamps();
            });
            
            // Restore data (excluding api_key and api_secret)
            foreach ($tools as $tool) {
                DB::table('tools')->insert([
                    'id' => $tool->id,
                    'name' => $tool->name,
                    'domain' => $tool->domain,
                    'logo' => $tool->logo,
                    'description' => $tool->description,
                    'api_url' => $tool->api_url ?? null,
                    'api_token' => $tool->api_token ?? null,
                    'webhook_url' => $tool->webhook_url ?? null,
                    'is_connected' => $tool->is_connected ?? false,
                    'last_ping_at' => $tool->last_ping_at ?? null,
                    'connection_metadata' => $tool->connection_metadata ?? null,
                    'status' => $tool->status ?? true,
                    'created_at' => $tool->created_at,
                    'updated_at' => $tool->updated_at,
                ]);
            }
        } else {
            // For MySQL/PostgreSQL
            Schema::table('tools', function (Blueprint $table) {
                $table->dropColumn(['api_key', 'api_secret']);
            });
        }
    }

    public function down(): void
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->string('api_key')->nullable();
            $table->string('api_secret')->nullable();
        });
    }
};