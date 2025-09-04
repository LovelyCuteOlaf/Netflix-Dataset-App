<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'user_id' => $this->user_id,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'age' => $this->age,
            'gender' => $this->gender,
            'country' => $this->country,
            'state_province' => $this->state_province,
            'city' => $this->city,
            'subscription_plan' => $this->subscription_plan,
            'subscription_start_date' => optional($this->subscription_start_date)->format('Y-m-d'),
            'is_active' => $this->is_active,
            'monthly_spend' => $this->monthly_spend,
            'primary_device' => $this->primary_device,
            'household_size' => $this->household_size,
            'created_at' => optional($this->created_at)->toIso8601String(),
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
            'movies' => MovieResource::collection($this->whenLoaded('movies')),
        ];
    }
}


