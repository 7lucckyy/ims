<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Months: string implements HasLabel
{
    case January = 'january';
    case February = 'february';
    case March = 'march';
    case April = 'april';
    case May = 'may';
    case June = 'june';
    case July = 'july';
    case August = 'august';
    case September = 'september';
    case October = 'october';
    case November = 'november';
    case December = 'december';

    public const DEFAULT = self::January;

    public static function values()
    {
        return array_column(self::cases(), 'value');
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::January => 'January',
            self::February => 'February',
            self::March => 'March',
            self::April => 'April',
            self::May => 'May',
            self::June => 'June',
            self::July => 'July',
            self::August => 'August',
            self::September => 'September',
            self::October => 'October',
            self::November => 'November',
            self::December => 'December',
        };
    }
}
