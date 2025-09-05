<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Skip for SQLite: it doesn't support MODIFY or TIME type in this manner
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // Switch created_at from TIMESTAMP/DATETIME to TIME(1) to store mm:ss.s precisely
        DB::statement('ALTER TABLE `users` MODIFY `created_at` TIME(1) NULL');
    }

    public function down(): void
    {
        // Skip for SQLite for the same reason as in up()
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // Revert to TIMESTAMP NULL (original migration used timestamp nullable)
        DB::statement('ALTER TABLE `users` MODIFY `created_at` TIMESTAMP NULL');
    }
};


