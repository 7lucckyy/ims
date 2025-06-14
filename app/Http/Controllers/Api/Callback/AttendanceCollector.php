<?php

namespace App\Http\Controllers\Api\Callback;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceCollector extends Controller
{
    public function collect(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'staff_id' => 'required|string',
        ]);

        $staffId = $validated['staff_id'];
        $today = Carbon::now('Africa/Lagos')->toDateString();

        // Check if user with this staff_id exists
        $user = User::whereRaw('"staffId" = ?', [$staffId])->first();


        if (! $user) {
            return response()->json([
                'message' => 'Staff record not found.',
            ], 404);
        }

        // Check attendance record for today
        $attendance = Attendance::where('staff_id', $staffId)
                                ->where('clock_in_date', $today)
                                ->first();

        if (! $attendance) {
            // Clock-in
            Attendance::create([
                'staff_id' => $staffId,
                'clock_in_date' => $today,
                'clock_in' => Carbon::now('Africa/Lagos')->toTimeString(),
            ]);

            return response()->json([
                'message' => 'Clock-in successful.',
            ], 201);
        }

        if ($attendance->clock_out !== null) {
            return response()->json([
                'message' => 'Already clocked out today.',
            ], 200);
        }

        // Clock-out
        $attendance->update([
            'clock_out' => Carbon::now('Africa/Lagos')->toTimeString(),
        ]);

        return response()->json([
            'message' => 'Clock-out successful.',
        ], 200);
    }
}
