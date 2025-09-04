<?php

namespace App\Console\Commands;

use App\Imports\MoviesImporter;
use App\Imports\ReviewsImporter;
use App\Imports\UsersImporter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportDataset extends Command
{
    protected $signature = 'import:dataset {--users=datasets/users.csv} {--movies=datasets/movies.csv} {--reviews=datasets/reviews.csv} {--chunk=1000}';
    protected $description = 'Import users, movies, and reviews from CSV files';

    public function handle(): int
    {
        $chunkSize = (int) $this->option('chunk');

        DB::disableQueryLog();

        // Avoid DST-related invalid timestamp issues by using UTC for this session
        try { DB::statement('SET time_zone = "+00:00"'); } catch (\Throwable $e) { /* ignore if not supported */ }

        $usersImporter = new UsersImporter();
        $moviesImporter = new MoviesImporter();
        $reviewsImporter = new ReviewsImporter();

        // Always import in FK-safe order
        $this->info("Importing users from {$this->option('users')}...");
        $usersImporter->import($this->option('users'), $chunkSize, fn($m) => $this->info($m));

        $this->info("Importing movies from {$this->option('movies')}...");
        $moviesImporter->import($this->option('movies'), $chunkSize, fn($m) => $this->info($m));

        $this->info("Importing reviews from {$this->option('reviews')}...");
        $reviewsImporter->import($this->option('reviews'), $chunkSize, fn($m) => $this->info($m));

        $this->info('Import finished.');
        return self::SUCCESS;
    }

    // Import logic has been refactored into importer classes under App\Imports
}


