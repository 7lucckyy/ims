<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Mokhosh\FilamentKanban\Concerns\IsKanbanStatus;

enum TaskStatus: string implements HasColor, HasIcon
{
    use IsKanbanStatus;

    case Todo = 'Todo';

    case InProgress = 'In Progress';

    case Reviewing = 'Reviewing';

    case Done = 'Done';

    public const DEFAULT = self::Todo;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Todo => 'gray',
            self::InProgress => 'warning',
            self::Reviewing => 'info',
            self::Done => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Todo => 'heroicon-o-clock',
            self::InProgress => 'heroicon-o-arrow-path',
            self::Reviewing => 'heroicon-o-eye',
            self::Done => 'heroicon-o-check-badge',
        };
    }
}
