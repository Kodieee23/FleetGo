<!DOCTYPE html>
<html>
<head>
    <title>FleetGo Trips Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            margin: 0;
            color: #1A4B8C;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        .status-completed {
            color: green;
        }
        .status-active {
            color: orange;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>FleetGo System</h2>
        <p>Trips Report</p>
        <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Driver</th>
                <th>Vehicle</th>
                <th>Destination</th>
                <th>Time Out</th>
                <th>Time Returned</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trips as $trip)
            <tr>
                <td>{{ $trip->created_at->format('Y-m-d') }}</td>
                <td>{{ $trip->driver->first_name ?? 'Unknown' }}</td>
                <td>{{ $trip->vehicle->name ?? 'N/A' }}</td>
                <td>{{ $trip->destination }}</td>
                <td>{{ $trip->time_out ? \Carbon\Carbon::parse($trip->time_out)->format('H:i') : '' }}</td>
                <td>{{ $trip->time_returned ? \Carbon\Carbon::parse($trip->time_returned)->format('H:i') : 'N/A' }}</td>
                <td class="{{ $trip->time_returned ? 'status-completed' : 'status-active' }}">
                    {{ $trip->time_returned ? 'Completed' : 'On Trip' }}
                </td>
            </tr>
            @endforeach
            @if($trips->isEmpty())
            <tr>
                <td colspan="7" style="text-align:center;">No trips found for the selected criteria.</td>
            </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
