@extends('layouts.app')

@section('page-content')
<div class="max-w-7xl mx-auto bg-white rounded-xl shadow-lg p-8">
    <div class="flex items-center gap-4 mb-6">
        <img src="{{ $employee->emp_pic ? asset('storage/' . $employee->emp_pic) : 'https://via.placeholder.com/100' }}"
             class="w-16 h-16 rounded-full border object-cover">
        <div>
            <h2 class="text-2xl font-bold text-blue-700">{{ $employee->user->name }}</h2>
            <p class="text-gray-600">{{ $employee->position }}</p>
        </div>
    </div>

    <div class="mb-4 flex justify-between items-center">
        <h3 class="text-xl font-semibold text-gray-800">
            Attendance for week: {{ $startOfWeek->format('M d, Y') }} - {{ $endOfWeek->format('M d, Y') }}
        </h3>
        <div class="flex gap-2">
            @if($canPrev)
                <a href="{{ route('employees.attendance.page', [$employee->id, 'week' => $week + 1]) }}"
                   class="px-3 py-1.5 border rounded-lg hover:bg-gray-100 transition">Previous Week</a>
            @endif
            @if($week > 0)
                <a href="{{ route('employees.attendance.page', [$employee->id, 'week' => $week - 1]) }}"
                   class="px-3 py-1.5 border rounded-lg hover:bg-gray-100 transition">Next Week</a>
            @endif
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300 rounded-lg">
            <thead class="bg-blue-100">
                <tr>
                    <th class="px-3 py-2 border">Date</th>
                    <th class="px-3 py-2 border">Time In / Time Out</th>
                    <th class="px-3 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $a)
                    <tr>
                        <td class="px-3 py-2 border">{{ \Carbon\Carbon::parse($a->date)->format('D, M d, Y') }}</td>
                        <td class="px-3 py-2 border">
                            <div>
                                <span class="font-semibold text-green-700">In:</span>
                                {{ $a->time_in ? \Carbon\Carbon::parse($a->time_in)->format('h:i A') : '-' }}
                            </div>
                            <div>
                                <span class="font-semibold text-red-700">Out:</span>
                                {{ $a->time_out ? \Carbon\Carbon::parse($a->time_out)->format('h:i A') : '-' }}
                            </div>
                        </td>
                        <td class="px-3 py-2 border">
                            <button class="btn btn-xs btn-warning"
                                onclick="openEditAttendance({{ $a->id }}, '{{ $a->time_in ?? '' }}', '{{ $a->time_out ?? '' }}', '{{ $a->date }}')">
                                <i class="bx bx-edit"></i> Update
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-gray-400 py-4">No attendance records for this week.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@include('partials.editAttendanceModal')

<script>
function openEditAttendance(attendanceId, timeIn = '', timeOut = '', date = '') {
    document.getElementById('edit_attendance_id').value = attendanceId;
    document.getElementById('edit_time_in').value = timeIn ? timeIn.slice(0, 5) : '';
    document.getElementById('edit_time_out').value = timeOut ? timeOut.slice(0, 5) : '';
    document.getElementById('editAttendanceModal').showModal();

    // Disable logic
    const today = new Date().toISOString().slice(0, 10);
    const isToday = date === today;
    document.getElementById('edit_time_in').disabled = isToday && !!timeIn;
    document.getElementById('edit_time_out').disabled = !timeIn;
}

document.getElementById('editAttendanceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('edit_attendance_id').value;
    let timeIn = document.getElementById('edit_time_in').value;
    let timeOut = document.getElementById('edit_time_out').value;

    timeIn = timeIn ? timeIn + ':00' : null;
    timeOut = timeOut ? timeOut + ':00' : null;

    fetch(`/attendance/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ time_in: timeIn, time_out: timeOut })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Failed to update attendance.');
        }
    })
    .catch(err => console.error(err));
});
</script>
@endsection
