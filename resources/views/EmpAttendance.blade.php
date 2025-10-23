@extends('layouts.app')

@section('page-content')
    <div class="max-w-5xl mx-auto bg-white rounded-xl shadow-lg p-8">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div class="flex items-center gap-4">
                <img src="{{ $employee->emp_pic ? asset('storage/' . $employee->emp_pic) : 'https://via.placeholder.com/100' }}"
                    class="w-16 h-16 rounded-full border object-cover">
                <div>
                    <h2 class="text-2xl font-bold text-blue-700">{{ $employee->user->name }}</h2>
                    <p class="text-gray-600">{{ $employee->position }}</p>
                </div>
            </div>

            <a href="{{ route('index') }}" class="border border-gray-300 rounded-md px-4 py-2 hover:bg-gray-100 transition">
                Back
            </a>

        </div>

        <div class="mb-4 flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">
                Attendance: {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} —
                {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
            </h3>

            <a href="{{ route('employees.attendance.export', $employee->id) }}"
                class="ml-4 inline-flex items-center gap-2 px-3 py-1.5 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                <i class="bx bx-download"></i> Export CSV
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 rounded-lg">
                <thead class="bg-blue-100">
                    <tr>
                        <th class="px-3 py-2 border text-left">Date</th>
                        <th class="px-3 py-2 border text-left">Time In / Time Out</th>
                        <th class="px-3 py-2 border text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($weekDates as $date)
                        @php
                            $key = $date->toDateString();
                            $a = $attendances->get($key);
                        @endphp

                        <tr id="attendance-row-{{ $a->id ?? 'date' . $date->format('Ymd') }}">
                            <td class="px-3 py-2 border">{{ $date->format('D, M d, Y') }}</td>
                            <td class="px-3 py-2 border attendance-times">
                                {{ $a ? \Carbon\Carbon::parse($a->time_in)->format('h:i A') ?? '-' : '-' }}
                                -
                                {{ $a ? \Carbon\Carbon::parse($a->time_out)->format('h:i A') ?? 'No record yet' : 'No record' }}
                            </td>
                            <td class="px-3 py-2 border">
                                @if ($a)
                                    <button
                                        class="flex items-center gap-1 px-3 py-1.5 bg-yellow-500 text-white rounded-lg hover:bg-yellow-500 transition"
                                        onclick="openEditAttendance({{ $a->id }}, '{{ $a->time_in ?? '' }}', '{{ $a->time_out ?? '' }}', '{{ $a->date }}')">
                                        <i class="bx bx-edit"></i> Edit Attendance
                                    </button>
                                @else
                                    <button
                                        class="flex items-center gap-1 px-3 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
                                        onclick="openEditAttendance(null, '', '', '{{ $date->toDateString() }}')">
                                        <i class="bx bx-plus"></i> Add Attendance
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-gray-400 py-4">No attendance dates to display.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('partials.editAttendanceModal')

    <script>
const employeeId = {{ $employee->id }};

function openEditAttendance(attendanceId = null, timeIn = '', timeOut = '', date = '') {
    const editId = document.getElementById('edit_attendance_id');
    const editEmp = document.getElementById('edit_attendance_emp_id');
    const editDate = document.getElementById('edit_attendance_date');
    const editTimeIn = document.getElementById('edit_time_in');
    const editTimeOut = document.getElementById('edit_time_out');
    const modal = document.getElementById('editAttendanceModal');

    if (editId) editId.value = attendanceId ?? '';
    if (editEmp) editEmp.value = employeeId;
    if (editDate) editDate.value = date ?? '';
    if (editTimeIn) editTimeIn.value = timeIn ? timeIn.slice(0,5) : '';
    if (editTimeOut) editTimeOut.value = timeOut ? timeOut.slice(0,5) : '';

    if (modal && typeof modal.showModal === 'function') {
        modal.showModal();
    } else {
        console.error('Modal element not found or showModal not supported.');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('editAttendanceForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const id = (document.getElementById('edit_attendance_id') || {}).value || '';
        const empId = (document.getElementById('edit_attendance_emp_id') || {}).value || employeeId;
        const date = (document.getElementById('edit_attendance_date') || {}).value || '';
        let timeIn = (document.getElementById('edit_time_in') || {}).value || '';
        let timeOut = (document.getElementById('edit_time_out') || {}).value || '';

        timeIn = timeIn ? timeIn + ':00' : null;
        timeOut = timeOut ? timeOut + ':00' : null;

        const isCreate = !id;
        const url = isCreate ? `{{ route('attendance.store') }}` : `/attendance/${id}`;
        const method = isCreate ? 'POST' : 'PUT';

        const payload = isCreate
            ? { emp_id: empId, date: date, time_in: timeIn, time_out: timeOut }
            : { time_in: timeIn, time_out: timeOut };

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (!data || !data.success) {
                alert(data?.message || 'Failed to save attendance.');
                return;
            }

            const a = data.data;
            const dateKey = date.replace(/-/g, '');
            const placeholderId = 'attendance-row-date' + dateKey;
            const newRowId = 'attendance-row-' + a.id;
            let row = document.getElementById(newRowId) || document.getElementById(placeholderId);

            const timesHtml = (a.time_in ? formatTime(a.time_in) : '-') + ' - ' + (a.time_out ? formatTime(a.time_out) : 'No record yet');
            const actionHtml = `<button class="flex items-center gap-1 px-3 py-1.5 bg-yellow-500 text-white rounded-lg hover:bg-yellow-500 transition"
                                    onclick="openEditAttendance(${a.id}, '${a.time_in ?? ''}', '${a.time_out ?? ''}', '${a.date}')">
                                    <i class="bx bx-edit"></i> Edit Attendance
                                </button>`;

            if (row) {
                row.id = newRowId;
                const timesCell = row.querySelector('.attendance-times');
                if (timesCell) timesCell.innerHTML = timesHtml;
                const actionCell = row.querySelector('td:last-child');
                if (actionCell) actionCell.innerHTML = actionHtml;
            }

            const modal = document.getElementById('editAttendanceModal');
            if (modal && typeof modal.close === 'function') modal.close();

            alert(data.message || 'Saved.');
        })
        .catch(err => {
            console.error(err);
            alert('Error saving attendance.');
        });
    });
});

function formatTime(val) {
    if (!val) return '-';
    const parts = val.split(':');
    let h = parseInt(parts[0], 10);
    const m = (parts[1] || '00').padStart(2, '0');
    const ampm = h >= 12 ? 'PM' : 'AM';
    h = h % 12;
    h = h ? h : 12;
    return `${h.toString().padStart(2, '0')}:${m} ${ampm}`;
}
    </script>
@endsection
