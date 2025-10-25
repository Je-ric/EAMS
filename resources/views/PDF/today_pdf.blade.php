<!DOCTYPE html>
<html>
<head>
    <title>Today's Attendance - {{ $today }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #007bff; color: white; }
        h2 { text-align: center; color: #007bff; }
    </style>
</head>
<body>
    <h2>Attendance Summary - {{ \Carbon\Carbon::parse($today)->format('F j, Y') }}</h2>

    <table>
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Position</th>
                <th>Email</th>
                <th>Time In</th>
                <th>Time Out</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attendances as $a)
                <tr>
                    <td>{{ $a->emp_id }}</td>
                    <td>{{ $a->employee->user->name ?? 'N/A' }}</td>
                    <td>{{ $a->employee->position ?? 'N/A' }}</td>
                    <td>{{ $a->employee->user->email ?? 'N/A' }}</td>
                    <td>{{ $a->time_in ? \Carbon\Carbon::parse($a->time_in)->format('h:i A') : '-' }}</td>
                    <td>{{ $a->time_out ? \Carbon\Carbon::parse($a->time_out)->format('h:i A') : '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="6">No attendance records found.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
