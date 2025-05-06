<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    use HasFactory;

    public const ADMIN = 1;

    public const STAFF = 2;

    public const LOGISTICS = 3;

    public const FINANCE = 4;

    public const HR = 5;

    public const PROCUREMENT = 6;

    public const MEAL = 7;

    public const DRIVER = 8;

    public const PROGRAM = 8;

    public const DONOR = 8;
}
