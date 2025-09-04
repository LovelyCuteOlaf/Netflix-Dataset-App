<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'email',
        'first_name',
        'last_name',
        'age',
        'gender',
        'country',
        'state_province',
        'city',
        'subscription_plan',
        'subscription_start_date',
        'is_active',
        'monthly_spend',
        'primary_device',
        'household_size',
        'created_at',
    ];

    protected $casts = [
        'age' => 'integer',
        'is_active' => 'boolean',
        'monthly_spend' => 'decimal:2',
        'household_size' => 'integer',
        'subscription_start_date' => 'date',
        // created_at is a TIME(1) mm:ss.s string
        'created_at' => 'string',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id', 'user_id');
    }

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'reviews', 'user_id', 'movie_id', 'user_id', 'movie_id')
            ->withPivot(['review_id', 'rating', 'review_date'])
            ->as('review');
    }
}
