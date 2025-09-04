<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->integer('per_page', 15);

        $query = Movie::query();

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->string('title') . '%');
        }
        if ($request->filled('content_type')) {
            $query->where('content_type', $request->string('content_type'));
        }
        if ($request->filled('genre')) {
            $genre = $request->string('genre');
            $query->where(function ($q) use ($genre) {
                $q->where('genre_primary', $genre)
                  ->orWhere('genre_secondary', $genre);
            });
        }
        if ($request->filled('release_year')) {
            $query->where('release_year', (int) $request->integer('release_year'));
        }
        if ($request->filled('language')) {
            $query->where('language', $request->string('language'));
        }
        if ($request->filled('country')) {
            $query->where('country_of_origin', $request->string('country'));
        }
        if ($request->filled('is_netflix_original')) {
            $val = filter_var($request->input('is_netflix_original'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if ($val !== null) {
                $query->where('is_netflix_original', $val);
            }
        }
        if ($request->filled('imdb_min')) {
            $query->where('imdb_rating', '>=', (float) $request->input('imdb_min'));
        }
        if ($request->filled('imdb_max')) {
            $query->where('imdb_rating', '<=', (float) $request->input('imdb_max'));
        }

        $query->orderBy('title');

        $paginator = $query->paginate($perPage)->appends($request->query());
        return MovieResource::collection($paginator);
    }

    public function show(Movie $movie)
    {
        $movie->load(['reviews.user']);
        return new MovieResource($movie);
    }
}


