<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum BidStatus: string implements HasColor, HasIcon, HasLabel
{
    case Pending = 'pending';

    case Won = 'won';

    case Lost = 'lost';

    public const DEFAULT = self::Pending;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Pending => 'gray',
            self::Won => 'success',
            self::Lost => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Pending => 'heroicon-o-clock',
            self::Won => 'heroicon-o-hand-thumb-up',
            self::Lost => 'heroicon-o-hand-thumb-down',
        };
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Won => 'Won',
            self::Lost => 'Lost',
        };
    }
}
