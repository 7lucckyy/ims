<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'staff_id', 'staff_id');
    }

    public function getDurationAttribute()
    {
        if (!$this->clock_in || !$this->clock_out) {
            return null;
        }

        $start = \Carbon\Carbon::parse($this->clock_in_date . ' ' . $this->clock_in);
        $end = \Carbon\Carbon::parse($this->clock_in_date . ' ' . $this->clock_out);

        if ($end->lessThan($start)) {
            $end->addDay(); // Handle overnight shifts
        }

        $diff = $start->diff($end);
        return sprintf('%d hrs %d mins', $diff->h + ($diff->days * 24), $diff->i);
    }

}
