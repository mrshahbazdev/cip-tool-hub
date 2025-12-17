<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Check if columns exist before adding
        if (!Schema::hasColumn('transactions', 'package_id')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->foreignId('package_id')->after('user_id')->constrained()->onDelete('cascade');
            });
        }

        if (!Schema::hasColumn('transactions', 'currency')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->string('currency', 3)->default('EUR')->after('amount');
            });
        }

        if (!Schema::hasColumn('transactions', 'status')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->string('status', 20)->default('pending')->after('currency');
            });
        }

        if (!Schema::hasColumn('transactions', 'metadata')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->json('metadata')->nullable()->after('status');
            });
        }
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'package_id')) {
                $table->dropForeign(['package_id']);
                $table->dropColumn('package_id');
            }
            if (Schema::hasColumn('transactions', 'currency')) {
                $table->dropColumn('currency');
            }
            if (Schema::hasColumn('transactions', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('transactions', 'metadata')) {
                $table->dropColumn('metadata');
            }
        });
    }
};