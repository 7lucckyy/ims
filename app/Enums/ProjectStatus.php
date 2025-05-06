<?php

namespace App\Enums;

use Mokhosh\FilamentKanban\Concerns\IsKanbanStatus;

enum ProjectStatus: string
{
    use IsKanbanStatus;

    case Completed = 'Completed';

    case InProgress = 'In Progress';

    case Suspended = 'Suspended';

    case OnHold = 'On Hold';

    case Done = 'Done';

    public const DEFAULT = self::InProgress;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
