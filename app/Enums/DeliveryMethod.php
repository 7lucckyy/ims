<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DeliveryMethod: string implements HasLabel
{
    case Air = 'air';

    case Sea = 'sea';

    case HandCarry = 'hand_carry';

    case Road = 'road';

    case Other = 'other';

    public const DEFAULT = self::Road;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Air => 'Air',
            self::Sea => 'Sea',
            self::HandCarry => 'Hand Carry',
            self::Road => 'Road',
            self::Other => 'Other',
        };
    }
}
