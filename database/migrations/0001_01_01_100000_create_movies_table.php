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
        Schema::create('movies', function (Blueprint $table) {
            $table->string('movie_id')->primary();
            $table->string('title');
            $table->string('content_type');
            $table->string('genre_primary');
            $table->string('genre_secondary')->nullable();
            $table->unsignedSmallInteger('release_year')->nullable();
            $table->unsignedSmallInteger('duration_minutes')->nullable();
            $table->string('rating')->nullable();
            $table->string('language')->nullable();
            $table->string('country_of_origin')->nullable();
            $table->decimal('imdb_rating', 3, 1)->nullable();
            $table->unsignedBigInteger('production_budget')->nullable();
            $table->unsignedBigInteger('box_office_revenue')->nullable();
            $table->unsignedSmallInteger('number_of_seasons')->nullable();
            $table->unsignedSmallInteger('number_of_episodes')->nullable();
            $table->boolean('is_netflix_original')->default(false);
            $table->date('added_to_platform')->nullable();
            $table->boolean('content_warning')->default(false);

            // Filtering indexes
            $table->index('content_type');
            $table->index('genre_primary');
            $table->index('release_year');
            $table->index('language');
            $table->index('country_of_origin');
            $table->index('is_netflix_original');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};


