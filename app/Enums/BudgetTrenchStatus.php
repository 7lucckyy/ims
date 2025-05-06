<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BudgetTrenchStatus: string implements HasLabel
{
    case First = 'first';

    case Second = 'second';

    case Third = 'third';

    case Fourth = 'fourth';

    public const DEFAULT = self::First;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::First => 'First',
            self::Second => 'Second',
            self::Third => 'Third',
            self::Fourth => 'Fourth',
        };
    }
}
