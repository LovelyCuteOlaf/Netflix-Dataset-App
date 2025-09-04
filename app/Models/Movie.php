<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $table = 'movies';
    protected $primaryKey = 'movie_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'movie_id',
        'title',
        'content_type',
        'genre_primary',
        'genre_secondary',
        'release_year',
        'duration_minutes',
        'rating',
        'language',
        'country_of_origin',
        'imdb_rating',
        'production_budget',
        'box_office_revenue',
        'number_of_seasons',
        'number_of_episodes',
        'is_netflix_original',
        'added_to_platform',
        'content_warning',
    ];

    protected $casts = [
        'release_year' => 'integer',
        'duration_minutes' => 'integer',
        'imdb_rating' => 'decimal:1',
        'production_budget' => 'integer',
        'box_office_revenue' => 'integer',
        'number_of_seasons' => 'integer',
        'number_of_episodes' => 'integer',
        'is_netflix_original' => 'boolean',
        'added_to_platform' => 'date',
        'content_warning' => 'boolean',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class, 'movie_id', 'movie_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'reviews', 'movie_id', 'user_id', 'movie_id', 'user_id')
            ->withPivot(['review_id', 'rating', 'review_date'])
            ->as('review');
    }
}


