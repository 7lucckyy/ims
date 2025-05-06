<?php

namespace App\Enums;

use App\Trait\EnumToArray;

enum UserRole: string
{
    use EnumToArray;

    case Staff = 'staff';
    case Admin = 'admin';
    case Logistics = 'logistics';
    case Finance = 'finance';
    case Hr = 'hr';
    case Procurement = 'procurement';
    case Meal = 'meal';
}
