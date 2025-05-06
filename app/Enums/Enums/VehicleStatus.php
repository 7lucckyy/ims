<?php

namespace App\Enums\Enums;

use App\Trait\EnumToArray;

enum VehicleStatus: string
{
    use EnumToArray;

    case Pending = 'pending';
    case Accept = 'accept';

    case Decline = 'declined';
}
