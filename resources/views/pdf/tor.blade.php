<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Reference</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }
    </style>
</head>

<body>
    <h2 style="text-align: center; font-weight: bold;">ACTIVITY TERMS OF REFERENCE (TOR)</h2>

    <h4 style="font-style: italic;">Activity Description</h4>

    <div style="display: inline;">
        <table>
            <tr>
                <td style="font-weight: bold">Project</td>
                <td>{{ $termsOfReference->project->name }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold">Project Code</td>
                <td>{{ $termsOfReference->project->code }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold">Duty Station</td>
                <td>{{ $termsOfReference->duty_station }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold">Budget Holder</td>
                <td>{{ $termsOfReference->budgetHolder->name }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold">Location</td>
                <td>{{ $termsOfReference->location->name }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold">Budget Line</td>
                <td>{{ $termsOfReference->budgetTrench->code }}</td>
            </tr>
        </table>
    </div>

    {{-- Background --}}
    <h3 style="font-weight: bold;">Background</h3>

    {!! $termsOfReference->background !!}

    {{-- Justification --}}
    <h3 style="font-weight: bold;">Justification</h3>

    {!! $termsOfReference->justification !!}

    {{-- Project Output --}}
    <h3 style="font-weight: bold;">Project Output</h3>

    {!! $termsOfReference->project_output !!}

    {{-- Activity Objectives --}}
    <h3 style="font-weight: bold;">Activity Objectives</h3>

    {!! $termsOfReference->activity_objectives !!}

    {{-- Modalities of Implementation --}}
    <h3 style="font-weight: bold;">Modalities of Implementation</h3>

    {!! $termsOfReference->modalities_of_implementation !!}

    {{-- Micro Activities --}}
    <h3 style="font-weight: bold;">Micro Activities</h3>

    {!! $termsOfReference->micro_activities !!}

    {{-- Activity Expected Output --}}
    <h3 style="font-weight: bold;">Activity Expected Output</h3>

    {!! $termsOfReference->activity_expected_output !!}

    {{-- Budget --}}
    <h2>Budget</h2>
    <table>
        <tr>
            <th>Budget Line</th>
            <th>Description</th>
            <th>Unit Cost</th>
            <th>Quantity</th>
            <th>Frequency</th>
            <th>Amount</th>
        </tr>
        @foreach ($termsOfReference->budget as $item)
            <tr>
                <td>{{ $item['budget_line'] }}</td>
                <td>{{ $item['description'] }}</td>
                <td>{{ $item['unit_cost'] }}</td>
                <td>{{ $item['quantity'] }}</td>
                <td>{{ $item['frequency'] }}</td>
                <td>{{ $item['amount'] }}</td>
            </tr>
        @endforeach
    </table>

    {{-- Review & Authorization --}}
    <h3 style="font-weight: bold;">Review & Authorization</h3>

    <div style="display: inline;">
        <table>
            <tr>
                <td style="font-weight: bold">Prepared By:</td>
                <td>{{ $termsOfReference->preparedBy->name }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold">Title:</td>
                <td>{{ $termsOfReference->preparedBy->staffDetail->position->name ?: '-' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold">Signature:</td>
                <td><img src="{{ $termsOfReference->preparedBy->sign() }}" width="200" height="100" /></td>
            </tr>
            <tr>
                <td style="font-weight: bold">Date:</td>
                <td>{{ $termsOfReference->created_at->toDateString() }}</td>
            </tr>
        </table>
    </div>

    <p style="font-weight: bold">Budget availability Confirmed by:</p>{{ $termsOfReference->confirmedBy?->name ?: '-' }}

    <p style="font-weight: bold">Approved by:</p> {{ $termsOfReference->approvedBy?->name ?: '-' }}

    <p style="font-weight: bold">Authorized by:</p> {{ $termsOfReference->authorizedBy?->name ?: '-' }}

</body>

</html>