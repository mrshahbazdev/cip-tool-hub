<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('CIP Tools');
            $table->string('site_logo')->nullable();
            $table->string('site_favicon')->nullable();
            $table->text('site_description')->nullable();
            $table->text('footer_description')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->timestamps();
        });

        // Insert a default record so we always have one to edit
        \DB::table('settings')->insert([
            'site_name' => 'CIP Tools',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};