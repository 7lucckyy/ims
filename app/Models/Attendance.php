<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

   protected $fillable = [
        'staff_id', 
        'clock_in_date', 
        'clock_in_time', 
        'clock_out'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id', 'staffid');
    }

    public function getDurationAttribute()
    {
        if (!$this->clock_in_time || !$this->clock_out) {
            return null;
        }

        $start = Carbon::parse($this->clock_in_date . ' ' . $this->clock_in_time);
        $end = Carbon::parse($this->clock_in_date . ' ' . $this->clock_out);

        if ($end->lessThan($start)) {
            $end->addDay(); // Handle overnight shifts
        }

        $diff = $start->diff($end);
        return sprintf('%d hrs %d mins', $diff->h + ($diff->days * 24), $diff->i);
    }

}
