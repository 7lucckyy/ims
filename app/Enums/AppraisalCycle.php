<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum AppraisalCycle: string implements HasLabel
{
    case Annual = 'annual';

    case Biannual = 'biannual';

    case Continuous = 'continuous';

    case Quarterly = 'quarterly';

    case ProjectBased = 'project_based';

    public const DEFAULT = self::Annual;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Annual => 'Annual',
            self::Biannual => 'Biannual',
            self::Continuous => 'Continuous',
            self::Quarterly => 'Quarterly',
            self::ProjectBased => 'Project Based',
        };
    }
}
