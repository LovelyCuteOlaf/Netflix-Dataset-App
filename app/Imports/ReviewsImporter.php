<?php

namespace App\Imports;

use App\Models\Review;
use Illuminate\Support\Facades\DB;

class ReviewsImporter
{
    use ImportHelpers;

    public function import(string $path, int $chunkSize, callable $logger): void
    {
        if (!is_file($path)) { $logger("Reviews file not found: {$path}"); return; }
        $handle = fopen($path, 'r');
        if ($handle === false) { $logger('Unable to open reviews file.'); return; }
        $header = fgetcsv($handle);
        $batch = [];
        $count = 0;
        static $existingUsers = [];
        static $existingMovies = [];
        while (($row = fgetcsv($handle)) !== false) {
            $count++;
            $data = array_combine($header, $row);
            if (!$data) { continue; }
            if (!isset($data['user_id'], $data['movie_id'])) { continue; }
            $uid = $data['user_id'];
            $mid = $data['movie_id'];
            if (!array_key_exists($uid, $existingUsers)) {
                $existingUsers[$uid] = DB::table('users')->where('user_id', $uid)->exists();
            }
            if (!array_key_exists($mid, $existingMovies)) {
                $existingMovies[$mid] = DB::table('movies')->where('movie_id', $mid)->exists();
            }
            if (!$existingUsers[$uid] || !$existingMovies[$mid]) { continue; }

            $batch[] = [
                'review_id' => $data['review_id'],
                'user_id' => $data['user_id'],
                'movie_id' => $data['movie_id'],
                'rating' => (int) $this->toNullableInt($data['rating'] ?? null),
                'review_date' => $this->toNullableDate($data['review_date'] ?? null),
                'device_type' => $this->toNullableString($data['device_type'] ?? null),
                'is_verified_watch' => $this->toBool($data['is_verified_watch'] ?? null),
                'helpful_votes' => $this->toNullableInt($data['helpful_votes'] ?? null),
                'total_votes' => $this->toNullableInt($data['total_votes'] ?? null),
                'review_text' => $this->toNullableString($data['review_text'] ?? null),
                'sentiment' => $this->toNullableString($data['sentiment'] ?? null),
                'sentiment_score' => $this->toNullableFloat($data['sentiment_score'] ?? null),
            ];
            if (count($batch) >= $chunkSize) { $this->flush($batch); $batch = []; }
        }
        if ($batch) { $this->flush($batch); }
        fclose($handle);
        $logger("Imported reviews rows: {$count}");
    }

    private function flush(array $rows): void
    {
        Review::upsert($rows, ['review_id'], [
            'user_id','movie_id','rating','review_date','device_type','is_verified_watch','helpful_votes','total_votes','review_text','sentiment','sentiment_score'
        ]);
    }
}


