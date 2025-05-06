<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Request</title>
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
    <div style="display: flex; justify-content: center; align-items: center; height: 100vh;">
        <img src="{{ public_path('gpon.png') }}" />
    </div>

    <div>
        <p style="font-weight: bold;">PR No.</p>
        {{ $purchaseRequest->pr_number }}
    </div>

    <table>
        <tr>
            <td style="border-style: none; font-weight: bold;">Procurement Threshold</td>
            <td style="border-style: none; font-weight: bold;">Sole Quotation</td>
            <td style="border-style: none; font-weight: bold;">Negotiated Procedured</td>
        </tr>
        <tr>
            <td style="border-style: none;">
                {{ \Illuminate\Support\Number::currency($purchaseRequest->procurement_threshold, $purchaseRequest->currency->abbr) }}
            </td>
            <td style="border-style: none;">
                {{ \Illuminate\Support\Number::currency($purchaseRequest->sole_quotation, $purchaseRequest->currency->abbr) }}
            </td>
            <td style="border-style: none;">
                {{ \Illuminate\Support\Number::currency($purchaseRequest->negotiated_procedures, $purchaseRequest->currency->abbr) }}
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="border-style: none; font-weight: bold;">State</td>
            <td style="border-style: none; font-weight: bold;">Office</td>
            <td style="border-style: none; font-weight: bold;">Priority</td>
        </tr>
        <tr>
            <td style="border-style: none;"> {{ $purchaseRequest->state->name }} </td>
            <td style="border-style: none;"> {{ $purchaseRequest->office }} </td>
            <td style="border-style: none;"> {{ $purchaseRequest->priority->getLabel() }} </td>
        </tr>
    </table>

    <p style="font-weight: bold;">Destination of Goods</p>

    <div style="display: inline;">
        <table>
            <tr>
                <td style="font-weight: bold;">Name</td>
                <td> {{ $purchaseRequest->requestedBy->name }} </td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Email Address</td>
                <td> {{ $purchaseRequest->requestedBy->email }} </td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Phone No.</td>
                <td> {{ $purchaseRequest->requestedBy->staffDetail->phone_number }} </td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Address/Location</td>
                <td> {!! $purchaseRequest->address !!} </td>
            </tr>
        </table>
    </div>

    <p style="font-weight: bold;">Dates</p>

    <table>
        <tr>
            <td style="border-style: none; font-weight: bold;">Date request prepared</td>
            <td style="border-style: none; font-weight: bold;">Date goods are required</td>
            <td style="border-style: none; font-weight: bold;">Project end date</td>
        </tr>
        <tr>
            <td style="border-style: none;"> {{ $purchaseRequest->request_date }} </td>
            <td style="border-style: none;"> {{ $purchaseRequest->required_date }} </td>
            <td style="border-style: none;"> {{ $purchaseRequest->end_date }} </td>
        </tr>
    </table>

    <div>
        <p style="font-weight: bold;">Program/Department requesting</p>
        {{ $purchaseRequest->department->name }}
    </div>

    <div>
        <p style="font-weight: bold;">Purpose of Request</p>
        {!! $purchaseRequest->purpose !!}
    </div>

    <div>
        <p style="font-weight: bold;">Preferred method of delivery</p>
        {{ $purchaseRequest->delivery->getLabel() }}
    </div>

    <div>
        <p style="font-weight: bold;">Any donor requirements exceeding GPON Procurement Policy</p>
        {!! $purchaseRequest->donor_requirements !!}
    </div>

    <div>
        <p style="font-weight: bold;">Any import restrictions or limitations on transport of goods(if known)</p>
        {!! $purchaseRequest->import_restrictions !!}
    </div>

    <table>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Unit/Form</th>
            <th>Unit Cost</th>
            <th>Quantity</th>
            <th>Frequency</th>
            <th>Amount</th>
        </tr>
        @foreach ($purchaseRequest->items as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['description'] }}</td>
                <td>{{ $item['form'] }}</td>
                <td>{{ $item['unit_cost'] }}</td>
                <td>{{ $item['quantity'] }}</td>
                <td>{{ $item['frequency'] }}</td>
                <td>{{ $item['amount'] }}</td>
            </tr>
        @endforeach
    </table>

</body>

</html>