<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'review_id' => $this->review_id,
            'user_id' => $this->user_id,
            'movie_id' => $this->movie_id,
            'rating' => $this->rating,
            'review_date' => optional($this->review_date)->format('Y-m-d'),
            'device_type' => $this->device_type,
            'is_verified_watch' => $this->is_verified_watch,
            'helpful_votes' => $this->helpful_votes,
            'total_votes' => $this->total_votes,
            'review_text' => $this->review_text,
            'sentiment' => $this->sentiment,
            'sentiment_score' => $this->sentiment_score,
            'user' => new UserResource($this->whenLoaded('user')),
            'movie' => new MovieResource($this->whenLoaded('movie')),
        ];
    }
}


