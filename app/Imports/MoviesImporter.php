<?php

namespace App\Imports;

use App\Models\Movie;

class MoviesImporter
{
    use ImportHelpers;

    public function import(string $path, int $chunkSize, callable $logger): void
    {
        if (!is_file($path)) { $logger("Movies file not found: {$path}"); return; }
        $handle = fopen($path, 'r');
        if ($handle === false) { $logger('Unable to open movies file.'); return; }
        $header = fgetcsv($handle);
        $batch = [];
        $count = 0;
        while (($row = fgetcsv($handle)) !== false) {
            $count++;
            $data = array_combine($header, $row);
            if (!$data) { continue; }
            $batch[] = [
                'movie_id' => $data['movie_id'],
                'title' => $data['title'],
                'content_type' => $data['content_type'],
                'genre_primary' => $data['genre_primary'],
                'genre_secondary' => $this->toNullableString($data['genre_secondary'] ?? null),
                'release_year' => $this->toNullableInt($data['release_year'] ?? null),
                'duration_minutes' => $this->toNullableInt($data['duration_minutes'] ?? null),
                'rating' => $this->toNullableString($data['rating'] ?? null),
                'language' => $this->toNullableString($data['language'] ?? null),
                'country_of_origin' => $this->toNullableString($data['country_of_origin'] ?? null),
                'imdb_rating' => $this->toNullableFloat($data['imdb_rating'] ?? null),
                'production_budget' => $this->toNullableInt($data['production_budget'] ?? null),
                'box_office_revenue' => $this->toNullableInt($data['box_office_revenue'] ?? null),
                'number_of_seasons' => $this->toNullableInt($data['number_of_seasons'] ?? null),
                'number_of_episodes' => $this->toNullableInt($data['number_of_episodes'] ?? null),
                'is_netflix_original' => $this->toBool($data['is_netflix_original'] ?? null),
                'added_to_platform' => $this->toNullableDate($data['added_to_platform'] ?? null),
                'content_warning' => $this->toBool($data['content_warning'] ?? null),
            ];
            if (count($batch) >= $chunkSize) { $this->flush($batch); $batch = []; }
        }
        if ($batch) { $this->flush($batch); }
        fclose($handle);
        $logger("Imported movies rows: {$count}");
    }

    private function flush(array $rows): void
    {
        Movie::upsert($rows, ['movie_id'], [
            'title','content_type','genre_primary','genre_secondary','release_year','duration_minutes','rating','language','country_of_origin','imdb_rating','production_budget','box_office_revenue','number_of_seasons','number_of_episodes','is_netflix_original','added_to_platform','content_warning'
        ]);
    }
}


