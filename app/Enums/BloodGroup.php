<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BloodGroup: string implements HasLabel
{
    // a, b, ab, o
    case APos = 'a_positive';
    case BPos = 'b_positive';
    case ABPos = 'ab_positive';
    case OPos = 'o_positive';
    case ANeg = 'a_negative';
    case BNeg = 'b_negative';
    case ABNeg = 'ab_negative';
    case ONeg = 'o_negative';

    public const DEFAULT = self::APos;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::APos => 'A+',
            self::BPos => 'B+',
            self::ABPos => 'AB+',
            self::OPos => 'O+',
            self::ANeg => 'A-',
            self::BNeg => 'B-',
            self::ABNeg => 'AB-',
            self::ONeg => 'O-',
        };
    }
}
