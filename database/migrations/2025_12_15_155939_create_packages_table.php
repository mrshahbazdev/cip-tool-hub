<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->enum('duration_type', ['trial', 'days', 'months', 'years', 'lifetime']);
            $table->integer('duration_value')->nullable();
            $table->json('features')->nullable();
            $table->timestamps();
            
            $table->index('tool_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};