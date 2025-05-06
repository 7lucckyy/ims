<?php

namespace App\Enums;

enum ComplainStatus: string
{
    case Pending = 'pending';
    case Completed = 'completed';
}
