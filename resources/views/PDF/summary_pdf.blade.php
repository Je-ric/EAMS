<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attendance Summary ({{ $from }} - {{ $to }})</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        h2 { text-align: center; color: #1e3a8a; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background: #f0f4ff; }
        .date-header { margin-top: 20px; font-weight: bold; color: #2563eb; }
    </style>
</head>
<body>
    <h2>Attendance Summary</h2>
    <p><strong>Period:</strong> {{ $from }} to {{ $to }}</p>

    @foreach ($records as $date => $rows)
        <p class="date-header">Date: {{ $date }}</p>
        <table>
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Position</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $row)
                    <tr>
                        <td>{{ $row->employee }}</td>
                        <td>{{ $row->position }}</td>
                        <td>{{ $row->time_in ? \Carbon\Carbon::parse($row->time_in)->format('h:i A') : '-' }}</td>
                        <td>{{ $row->time_out ? \Carbon\Carbon::parse($row->time_out)->format('h:i A') : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>
</html>
