<?php

namespace App\Http\Controllers\Api\Callback;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceCollector extends Controller
{
    public function collect(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|integer',
        ]);

        $today = now()->toDateString();

        $attendance = Attendance::where('staff_id', $validated['staff_id'])
            ->where('clock_in_date', $today)
            ->first();

        // Clock in if no record exists
        if (! $attendance) {
            Attendance::create([
                'staff_id' => $validated['staff_id'],
                'clock_in_date' => $today,
                'clock_in' => now()->toTimeString(), // Optional: log time-in
            ]);

            return response()->json([
                'message' => 'Clock-in recorded successfully',
            ]);
        }

        // Prevent double clock-out
        if ($attendance->clock_out !== null) {
            return response()->json([
                'message' => 'Staff already clocked out today',
            ]);
        }

        // Clock out
        $attendance->update([
            'clock_out' => now()->toTimeString(),
        ]);

        return response()->json([
            'message' => 'Clock-out recorded successfully',
        ]);
    }
}
