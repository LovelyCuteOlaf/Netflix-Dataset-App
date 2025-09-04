<?php

namespace App\Imports;

trait ImportHelpers
{
    private function toNullableString($value): ?string
    {
        $value = trim((string) $value);
        return $value === '' ? null : $value;
    }

    private function toNullableInt($value): ?int
    {
        if ($value === null || $value === '') { return null; }
        if (!is_numeric($value)) { return null; }
        return (int) $value;
    }

    private function toNonNegativeIntOrNull($value): ?int
    {
        $int = $this->toNullableInt($value);
        if ($int === null) { return null; }
        return $int < 0 ? null : $int;
    }

    private function normalizeAge($value): ?int
    {
        $int = $this->toNullableInt($value);
        if ($int === null) { return null; }
        if ($int < 0 || $int > 120) { return null; }
        return $int;
    }

    private function toNullableFloat($value): ?float
    {
        if ($value === null || $value === '') { return null; }
        if (!is_numeric($value)) { return null; }
        return (float) $value;
    }

    private function toNullableDate($value): ?string
    {
        $value = $this->toNullableString($value);
        if ($value === null) { return null; }
        $ts = strtotime($value);
        return $ts ? date('Y-m-d', $ts) : null;
    }

    private function toTimeMmSsTenthsOrNull($value): ?string
    {
        $value = $this->toNullableString($value);
        if ($value === null) { return null; }
        if (!preg_match('/^\d{1,2}:\d{2}(?:\.\d)?$/', $value)) {
            return null;
        }
        $parts = explode(':', $value);
        $min = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
        $sec = $parts[1];
        if (strpos($sec, '.') === false) {
            $sec .= '.0';
        }
        $secParts = explode('.', $sec);
        $secWhole = str_pad($secParts[0], 2, '0', STR_PAD_LEFT);
        $tenths = substr($secParts[1], 0, 1);
        return "00:{$min}:{$secWhole}.{$tenths}";
    }

    private function toBool($value): bool
    {
        if (is_bool($value)) { return $value; }
        $value = strtolower(trim((string) $value));
        return in_array($value, ['1','true','yes','y','on'], true);
    }
}


