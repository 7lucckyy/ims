<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Reach: string implements HasLabel
{
    case Boys = 'boys';
    case Girls = 'girls';
    case Men = 'men';
    case Women = 'women';

    public const DEFAULT = self::Boys;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Boys => 'Boys',
            self::Girls => 'Girls',
            self::Men => 'Men',
            self::Women => 'Women',
        };
    }
}
