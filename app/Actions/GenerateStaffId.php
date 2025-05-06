<?php

namespace App\Actions;

use App\Enums\UserRole;
use App\Models\Staff;
use Random\RandomException;

class GenerateStaffId
{
    public static function generateStaffId(?string $role): string
    {
        $prefix = match ($role) {
            UserRole::Hr->value => 'hr',
            UserRole::Finance->value => 'fn',
            UserRole::Admin->value => 'ad',
            UserRole::Logistics->value => 'lg',
            UserRole::Procurement->value => 'pr',
            default => 'stf'
        };

        return self::generateUniqueStaffId($prefix);
    }

    /**
     * @throws RandomException
     */
    private static function generateUniqueStaffId(string $prefix): string
    {
        do {
            $staffId = strtoupper($prefix).'/'.date('Y').'/'.random_int(111111, 999999);
            $exists = Staff::where('staffId', $staffId)->exists();
        } while ($exists);

        return $staffId;
    }
}
