<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Timesheet Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            max-height: 80px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #2e7d32;
            /* Green color */
        }

        .subtitle {
            font-size: 18px;
            color: #388e3c;
            /* Darker green */
            margin-top: 5px;
        }

        .employee-info {
            margin: 20px 0;
            border: 1px solid #4caf50;
            /* Green border */
            border-radius: 5px;
            padding: 15px;
            background: #f1f8e9;
            /* Light green background */
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        .info-item {
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            color: #2e7d32;
            /* Green color */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            border: 2px solid #4caf50;
            /* Green border */
        }

        th,
        td {
            border: 1px solid #a5d6a7;
            /* Light green border */
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #e8f5e9;
            /* Light green header */
            font-weight: bold;
            color: #1b5e20;
            /* Dark green text */
        }

        .weekend {
            background-color: #f1f8e9;
            /* Light green */
            color: #558b2f;
            /* Green text */
        }

        .total-row {
            font-weight: bold;
            background-color: #c8e6c9;
            /* Medium green */
            color: #1b5e20;
            /* Dark green text */
        }

        .signature-section {
            margin-top: 40px;
            border-top: 2px solid #4caf50;
            /* Green border */
            padding-top: 20px;
        }

        .signature-line {
            width: 300px;
            border-bottom: 1px solid #4caf50;
            /* Green line */
            display: inline-block;
            margin: 0 10px;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #2e7d32;
            /* Green color */
            text-align: center;
        }

        .notes {
            margin-top: 20px;
            font-size: 12px;
            color: #2e7d32;
            /* Green color */
            font-style: italic;
        }

        .green-text {
            color: #2e7d32;
        }
    </style>
</head>

<body>
    <!-- Logo at the top center -->
    <div class="logo-container">
        <img src="{{ public_path('gpon.png') }}" alt="Company Logo">
    </div>

    <div class="header">
        <div class="title">Payroll Reporting Period</div>
        <div class="subtitle">
            {{ \Carbon\Carbon::parse($start_date)->format('F jS') }} to
            {{ \Carbon\Carbon::parse($end_date)->format('jS Y') }}
        </div>
    </div>

    <div class="employee-info">
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Name:</span> {{ $user->name }}
            </div>
            <div class="info-item">
                <span class="info-label">Position:</span> {{ $user->staffDetail->position->name ?? 'N/A' }}
            </div>
            <div class="info-item">
                <span class="info-label">Department:</span> {{ $user->staffDetail->department->name ?? 'N/A' }}

            </div>
            <div class="info-item">
                <span class="info-label">Staff ID:</span> {{ $user->staffid }}
            </div>
            <div class="info-item">
                <span class="info-label">Total Allocation:</span>
                {{ $projects->sum('pivot.project_involvement_percentage') }}%
            </div>
        </div>
    </div>


    <table>
        <thead>
            <tr>
                <th>Period</th>
                <th>Date/Day</th>
                <th>Start Time</th>
                <th>Finish Time</th>
                <th>Break Hours</th>
                <th>Hours Worked</th>
                <th>Time Spent (hrs)</th>
                <th>Project Code</th>
                <th>End Date</th>
                <th>% to Change</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalHours = 0;
                $today = now();
            @endphp

            @foreach ($period as $date)
                @php
                    $isWeekend = $date->isWeekend();
                    $attendance = $attendances[$date->format('Y-m-d')] ?? null;
                    $hoursWorked = 0;
                    $holiday = $holidays[$date->format('Y-m-d')] ?? null;

                    $leaveStart = \Carbon\Carbon::parse(array_key_first($leaves['start']));
                    $leaveEnd = \Carbon\Carbon::parse(array_key_first($leaves['end']));

                    if ($attendance && $attendance->clock_out) {
                        $start = \Carbon\Carbon::parse($attendance->clock_in_time);
                        $end = \Carbon\Carbon::parse($attendance->clock_out);
                        $hoursWorked = $end->diffInMinutes($start) / 60;
                        $totalHours += $hoursWorked;
                    }
                @endphp

                <tr class="{{ $isWeekend ? 'weekend' : '' }}">
                    <td>{{ $date->format('d') }}</td>
                    <td>{{ $date->format('l') }}</td>

                    @if ($isWeekend)
                        <td colspan="4" style="text-align: center; font-weight: bold;">WEEKEND</td>
                        <td></td>
                    @elseif($holiday)
                        <td colspan="4" style="text-align: center; font-weight: bold;">
                            {{ $holiday['name'] }} HOLIDAY
                        </td>
                        <td></td>
                    @elseif($date->diffInDays($leaveStart) <= 0 && $date->diffInDays($leaveEnd) >= 0)
                        <td colspan="4" style="text-align: center; font-weight: bold;">
                            ON LEAVE
                        </td>
                        <td></td>
                    @else
                        <td>{{ $attendance ? $attendance->clock_in_time : '-' }}</td>
                        <td>{{ $attendance && $attendance->clock_out ? $attendance->clock_out : '-' }}</td>
                        <td>0.00</td> <!-- Break Hours -->
                        <td>{{ $hoursWorked > 0 ? number_format($hoursWorked, 2) : '-' }}</td>
                    @endif

                    <td>{{ $hoursWorked > 0 ? number_format($hoursWorked, 2) : '-' }}</td>
                    <td></td> <!-- Client Code -->
                    <td>{{ $date->format('Y-m-d') }}</td>
                    <td></td> <!-- % to Change -->
                </tr>
            @endforeach

            <tr class="total-row">
                <td colspan="5" style="text-align: right;">TOTAL MUST BE:</td>
                <td>{{ number_format($totalHours, 2) }}</td>
                <td>{{ number_format($totalHours, 2) }}</td>
                <td colspan="3"></td>
            </tr>
        </tbody>
    </table>
    <div class="project-allocation">
        <h3 style="color: #2e7d32; border-bottom: 2px solid #4caf50; padding-bottom: 5px;">
            Project Allocation
        </h3>

        <table class="project-table">
            <thead>
                <tr>
                    <th>Project Code</th>
                    <th>Project Name</th>
                    <th>Allocation Percentage</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                    <tr>
                        <td>{{ $project->code }}</td>
                        <td>{{ $project->name }}</td>
                        <td>{{ $project->pivot->project_involvement_percentage }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="allocation-total">
            Total Allocation: {{ $projects->sum('pivot.project_involvement_percentage') }}%
        </div>
    </div>

    <div class="signature-section">
        <div class="notes">
            <p>The signatures on this time sheet attest to the fact that the percentage allocation by grants is a
                reasonable and true indication of the time and effort dedicated to each project for the period being
                reported.</p>
        </div>

        <div style="margin-top: 40px;">
            <p>Signature: <span class="signature-line"></span></p>
        </div>

        <div style="margin-top: 30px;">
            <p>Supervisor's verification: Name <span class="signature-line"></span> and Signature <span
                    class="signature-line"></span></p>
        </div>
    </div>

    <div class="footer">
        <p>Generated on: {{ now()->format('M d, Y h:i A') }}</p>
    </div>
</body>

</html>
