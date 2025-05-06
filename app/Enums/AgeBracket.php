<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum AgeBracket: string implements HasLabel
{
    case Toddler = 'toddler';
    case Kids = 'kids';
    case Teenagers = 'teenagers';
    case Adults = 'adults';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Toddler => '1-5',
            self::Kids => '6-12',
            self::Teenagers => '13-19',
            self::Adults => '20 & Above',
        };
    }
}
