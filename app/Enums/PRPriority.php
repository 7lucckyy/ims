<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PRPriority: string implements HasColor, HasLabel
{
    case Urgent = 'urgent';
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';

    public const DEFAULT = self::High;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Urgent => 'danger',
            self::High => 'warning',
            self::Low => 'gray',
            self::Medium => 'info',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Urgent => 'Urgent',
            self::High => 'High',
            self::Low => 'Low',
            self::Medium => 'Medium',
        };
    }
}
