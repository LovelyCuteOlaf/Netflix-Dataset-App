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
        Schema::create('reviews', function (Blueprint $table) {
            $table->string('review_id')->primary();
            $table->string('user_id');
            $table->string('movie_id');
            $table->unsignedTinyInteger('rating');
            $table->date('review_date')->nullable();
            $table->string('device_type')->nullable();
            $table->boolean('is_verified_watch')->default(false);
            $table->unsignedInteger('helpful_votes')->nullable();
            $table->unsignedInteger('total_votes')->nullable();
            $table->text('review_text')->nullable();
            $table->string('sentiment')->nullable();
            $table->decimal('sentiment_score', 4, 3)->nullable();

            $table->foreign('user_id')->references('user_id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('movie_id')->references('movie_id')->on('movies')->cascadeOnUpdate()->cascadeOnDelete();

            $table->index(['movie_id', 'rating']);
            $table->index(['user_id', 'rating']);
            $table->index('review_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};


