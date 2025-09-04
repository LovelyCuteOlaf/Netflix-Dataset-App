<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UsersImporter
{
    use ImportHelpers;

    public function import(string $path, int $chunkSize, callable $logger): void
    {
        if (!is_file($path)) { $logger("Users file not found: {$path}"); return; }
        $handle = fopen($path, 'r');
        if ($handle === false) { $logger('Unable to open users file.'); return; }
        $header = fgetcsv($handle);
        $batch = [];
        $count = 0;
        while (($row = fgetcsv($handle)) !== false) {
            $count++;
            $data = array_combine($header, $row);
            if (!$data) { continue; }
            $batch[] = [
                'user_id' => $data['user_id'],
                'email' => $data['email'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'age' => $this->normalizeAge($data['age'] ?? null),
                'gender' => $this->toNullableString($data['gender'] ?? null),
                'country' => $data['country'],
                'state_province' => $this->toNullableString($data['state_province'] ?? null),
                'city' => $this->toNullableString($data['city'] ?? null),
                'subscription_plan' => $data['subscription_plan'],
                'subscription_start_date' => $this->toNullableDate($data['subscription_start_date'] ?? null),
                'is_active' => $this->toBool($data['is_active'] ?? null),
                'monthly_spend' => $this->toNullableFloat($data['monthly_spend'] ?? null),
                'primary_device' => $this->toNullableString($data['primary_device'] ?? null),
                'household_size' => $this->toNonNegativeIntOrNull($data['household_size'] ?? null),
                'created_at' => $this->toTimeMmSsTenthsOrNull($data['created_at'] ?? null),
            ];
            if (count($batch) >= $chunkSize) { $this->flush($batch); $batch = []; }
        }
        if ($batch) { $this->flush($batch); }
        fclose($handle);
        $logger("Imported users rows: {$count}");
    }

    private function flush(array $rows): void
    {
        User::upsert($rows, ['user_id'], [
            'email','first_name','last_name','age','gender','country','state_province','city','subscription_plan','subscription_start_date','is_active','monthly_spend','primary_device','household_size','created_at'
        ]);
    }
}


