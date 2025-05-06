<?php

namespace App\Http\Controllers\Api\Callback;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceCollector extends Controller
{
    public function collect(Request $request)
    {
        $validatedData = $request->validate([
            'staff_id' => 'required',
        ]);

        $date = date('Y-m-d');

        //        dd(Carbon::now(), $date);
        $attendance = Attendance::where([
            'staff_id' => $validatedData['staff_id'],
            'clock_in_date' => $date,
        ]);

        //        dd($attendance);

        if (! $attendance->exists()) {
            Attendance::create([
                'staff_id' => $validatedData['staff_id'],
                'clock_in_date' => $date,
            ]);

            return response()->json([
                'message' => 'Attendance added successfully',
            ]);
        }

        $updateAttendance = $attendance->get()->first();

        if ($updateAttendance->clock_out != null) {
            return response()->json(['message' => 'Attendance already clocked out']);
        }

        $updateAttendance->update([
            'clock_out' => Carbon::now()->toTimeString(),
            //            'clock_out' => Carbon::now()->toIso8601String(),
        ]);

        return response()->json([
            'message' => 'Attendance updated successfully',
        ]);
    }
}
