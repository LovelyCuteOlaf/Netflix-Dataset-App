<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Dataset users table based on users.csv
        Schema::create('users', function (Blueprint $table) {
            $table->string('user_id')->primary();
            $table->string('email')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->unsignedSmallInteger('age')->nullable();
            $table->string('gender')->nullable();
            $table->string('country');
            $table->string('state_province')->nullable();
            $table->string('city')->nullable();
            $table->string('subscription_plan');
            $table->date('subscription_start_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->decimal('monthly_spend', 10, 2)->nullable();
            $table->string('primary_device')->nullable();
            $table->unsignedSmallInteger('household_size')->nullable();
            // Dataset contains a created_at-like column; store as datetime if available
            $table->timestamp('created_at')->nullable();

            // Useful indexes for filtering
            $table->index('country');
            $table->index('subscription_plan');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
