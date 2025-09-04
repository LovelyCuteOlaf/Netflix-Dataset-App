<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop unique index created by the original migration
            try {
                $table->dropUnique('users_email_unique');
            } catch (\Throwable $e) {
                // If the index name differs or already dropped, ignore
            }
            // Replace with a normal index for lookups
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            try {
                $table->dropIndex(['email']);
            } catch (\Throwable $e) {
            }
            // Attempt to restore unique constraint
            $table->unique('email');
        });
    }
};


