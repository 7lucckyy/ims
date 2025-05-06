<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DocumentType: string implements HasLabel
{
    case Attachment = 'attachment';

    case Budget = 'budget';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Attachment => 'Attachment',
            self::Budget => 'Budget',
        };
    }
}
