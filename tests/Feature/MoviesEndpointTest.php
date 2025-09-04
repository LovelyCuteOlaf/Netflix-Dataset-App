<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class MoviesEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function test_movies_index_responds_with_pagination(): void
    {
        // Minimal seed: create table structures only (RefreshDatabase runs migrations)
        // Hitting endpoint without data should still return a paginated structure
        $response = $this->getJson('/api/movies?per_page=2');

        $response->assertOk()
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data')
                     ->has('links')
                     ->has('meta')
            );
    }
}


