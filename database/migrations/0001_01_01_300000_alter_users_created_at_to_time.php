<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Switch created_at from TIMESTAMP/DATETIME to TIME(1) to store mm:ss.s precisely
        DB::statement('ALTER TABLE `users` MODIFY `created_at` TIME(1) NULL');
    }

    public function down(): void
    {
        // Revert to TIMESTAMP NULL (original migration used timestamp nullable)
        DB::statement('ALTER TABLE `users` MODIFY `created_at` TIMESTAMP NULL');
    }
};


