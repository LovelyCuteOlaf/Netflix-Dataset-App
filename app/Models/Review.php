<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';
    protected $primaryKey = 'review_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'review_id',
        'user_id',
        'movie_id',
        'rating',
        'review_date',
        'device_type',
        'is_verified_watch',
        'helpful_votes',
        'total_votes',
        'review_text',
        'sentiment',
        'sentiment_score',
    ];

    protected $casts = [
        'rating' => 'integer',
        'review_date' => 'date',
        'is_verified_watch' => 'boolean',
        'helpful_votes' => 'integer',
        'total_votes' => 'integer',
        'sentiment_score' => 'decimal:3',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id', 'movie_id');
    }
}


