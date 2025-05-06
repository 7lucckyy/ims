<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Packaging: string implements HasLabel
{
    case Bottle = 'Bottle';
    case Cans = 'Cans';
    case Jars = 'Jars';
    case BlisterPacks = 'Blister Packs';
    case Tubes = 'Tubes';
    case Pouches = 'Pouches';
    case CartonsBoxes = 'Cartons & Boxes';
    case ShrinkWrap = 'Shrink Wrap';
    case Trays = 'Trays';
    case Pallets = 'Pallets';
    case BarrelsDrums = 'Barrels & Drums';
    case CorrugatedBoxes = 'Corrugated Boxes';
    case StretchWrap = 'Stretch Wrap & Straps';

    public const DEFAULT = self::CartonsBoxes;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Bottle => 'Bottle',
            self::Cans => 'Cans',
            self::Jars => 'Jars',
            self::BlisterPacks => 'Blister Packs',
            self::Tubes => 'Tubes',
            self::Pouches => 'Pouches',
            self::CartonsBoxes => 'Cartons & Boxes',
            self::ShrinkWrap => 'Shrink Wrap',
            self::Trays => 'Trays',
            self::Pallets => 'Pallets',
            self::BarrelsDrums => 'Barrels & Drums',
            self::CorrugatedBoxes => 'Corrugated Boxes',
            self::StretchWrap => 'Stretch Wrap & Straps',
        };
    }
}
