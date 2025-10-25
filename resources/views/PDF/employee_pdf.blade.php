<!DOCTYPE html>
<html>
<head>
    <title>Attendance Report - {{ $employee->user->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #007bff; color: white; }
        h2 { text-align: center; color: #007bff; }
        .meta { margin-bottom: 10px; }
    </style>
</head>
<body>
    <h2>Employee Attendance Report</h2>
    <div class="meta">
        <strong>Name:</strong> {{ $employee->user->name }} <br>
        <strong>Position:</strong> {{ $employee->position }} <br>
        <strong>Email:</strong> {{ $employee->user->email }} <br>
        <strong>Generated:</strong> {{ now()->format('F j, Y g:i A') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Time In</th>
                <th>Time Out</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attendances as $a)
                <tr>
                    <td>{{ $a->date }}</td>
                    <td>{{ $a->time_in ? \Carbon\Carbon::parse($a->time_in)->format('h:i A') : '-' }}</td>
                    <td>{{ $a->time_out ? \Carbon\Carbon::parse($a->time_out)->format('h:i A') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No attendance records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
