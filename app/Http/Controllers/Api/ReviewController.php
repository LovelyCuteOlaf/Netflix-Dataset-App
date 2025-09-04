<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->integer('per_page', 15);
        $query = Review::query()->with(['user', 'movie']);

        if ($request->filled('movie_id')) {
            $query->where('movie_id', $request->string('movie_id'));
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->string('user_id'));
        }
        if ($request->filled('rating')) {
            $query->where('rating', (int) $request->integer('rating'));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('review_date', '>=', $request->string('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('review_date', '<=', $request->string('date_to'));
        }

        $query->orderByDesc('review_date');

        $paginator = $query->paginate($perPage)->appends($request->query());
        return ReviewResource::collection($paginator);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'review_id' => ['required', 'string', 'unique:reviews,review_id'],
            'user_id' => ['required', 'string', 'exists:users,user_id'],
            'movie_id' => ['required', 'string', 'exists:movies,movie_id'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'review_date' => ['nullable', 'date'],
            'device_type' => ['nullable', 'string'],
            'is_verified_watch' => ['nullable', 'boolean'],
            'helpful_votes' => ['nullable', 'integer', 'min:0'],
            'total_votes' => ['nullable', 'integer', 'min:0'],
            'review_text' => ['nullable', 'string'],
            'sentiment' => ['nullable', 'string', Rule::in(['positive','neutral','negative'])],
            'sentiment_score' => ['nullable', 'numeric', 'between:0,1'],
        ]);

        $review = Review::create($data);
        return (new ReviewResource($review->load(['user','movie'])))->response()->setStatusCode(201);
    }

    public function show(Review $review)
    {
        $review->load(['user', 'movie']);
        return new ReviewResource($review);
    }

    public function update(Request $request, Review $review)
    {
        $data = $request->validate([
            'user_id' => ['sometimes', 'string', 'exists:users,user_id'],
            'movie_id' => ['sometimes', 'string', 'exists:movies,movie_id'],
            'rating' => ['sometimes', 'integer', 'between:1,5'],
            'review_date' => ['nullable', 'date'],
            'device_type' => ['nullable', 'string'],
            'is_verified_watch' => ['nullable', 'boolean'],
            'helpful_votes' => ['nullable', 'integer', 'min:0'],
            'total_votes' => ['nullable', 'integer', 'min:0'],
            'review_text' => ['nullable', 'string'],
            'sentiment' => ['nullable', 'string', Rule::in(['positive','neutral','negative'])],
            'sentiment_score' => ['nullable', 'numeric', 'between:0,1'],
        ]);

        $review->update($data);
        return new ReviewResource($review->fresh()->load(['user','movie']));
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return response()->noContent();
    }
}


