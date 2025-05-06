<?php

namespace App\Helpers;

use App\Models\Staff;

class Helper
{
    public static function getAuthStaffId()
    {
        $email = auth()->user()->email;

        return Staff::where('email', $email)?->first()?->id;
    }
}
