@extends('layouts.app')

@section('page-content')
    <div class="container mx-auto my-12 p-8 bg-white shadow-xl rounded-2xl border border-gray-200">

        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">
                    Employee Attendance
                </h1>
                @auth
                    @if (Auth::user()->role === 'admin')
                        <p class="text-gray-600 text-sm mt-1">
                            Logged in as: <span class="font-semibold text-blue-600">{{ Auth::user()->name }}</span>
                        </p>
                    @endif
                @endauth
            </div>

            <div class="flex gap-3 items-center w-full md:w-auto">
                <div class="flex items-center border rounded-lg overflow-hidden">
                    <button class="px-3 py-2 text-gray-500 hover:bg-gray-100 transition"><i class='bx bx-search'></i></button>
                    <input type="text" id="searchInput" placeholder="Search here..."
                        class="flex-1 px-3 py-2 focus:outline-none">
                </div>

                @auth
                    @if (Auth::user()->role === 'admin')
                        <button
                            class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition"
                            onclick="attendanceSummaryModal.showModal()">
                            <i class='bx bx-clipboard'></i> View Attendance Summary
                        </button>

                        <button
                            class="flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-lg shadow hover:bg-green-600 transition"
                            onclick="addModal.showModal()">
                            <i class='bx bx-user-plus'></i> Add Employee
                        </button>

                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center gap-2 px-4 py-2 bg-red-500 text-white rounded-lg shadow hover:bg-red-600 transition">
                                <i class='bx bx-log-out'></i> Logout
                            </button>
                        </form>
                    @endif
                @else
                    <button
                        class="flex items-center gap-2 px-4 py-2 bg-gray-700 text-white rounded-lg shadow hover:bg-gray-800 transition"
                        onclick="document.getElementById('adminDialog').showModal()">
                        <i class='bx bx-shield'></i> Admin
                    </button>
                @endauth
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-center border border-gray-200 rounded-lg table-auto">
                <thead class="bg-gradient-to-r from-blue-600 to-blue-500 text-white text-lg uppercase">
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">Profile</th>
                        <th class="px-4 py-2">Full Name</th>
                        <th class="px-4 py-2">Position</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $index => $employee)
                        <tr class="hover:bg-blue-50 transition-colors">
                            <td class="px-4 py-2 font-medium text-gray-700">{{ $index + 1 }}</td>
                            <td class="px-4 py-2">
                                <img src="{{ $employee->emp_pic ? asset('storage/' . $employee->emp_pic) : 'https://via.placeholder.com/80x50' }}"
                                    alt="Profile"
                                    class="mx-auto rounded-full border border-gray-300 w-12 h-12 object-cover">
                            </td>
                            <td class="px-4 py-2 text-gray-800 font-semibold">{{ $employee->user->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2 text-gray-600">{{ $employee->position }}</td>
                            <td class="px-4 py-2">
                                @auth
                                    @if (Auth::user()->role === 'admin')
                                        <div class="flex justify-center gap-2 flex-wrap">
                                            <button
                                                class="flex items-center gap-1 px-3 py-1.5 bg-yellow-400 text-gray-900 font-semibold rounded-lg shadow hover:bg-yellow-500 transition"
                                                onclick="openUpdateModal({{ $employee->id }}, {{ $employee->user->id ?? 'null' }}, '{{ addslashes($employee->user->name ?? '') }}', '{{ addslashes($employee->position) }}', '{{ addslashes($employee->user->email ?? '') }}', '{{ addslashes($employee->emp_pic ? asset('storage/' . $employee->emp_pic) : asset('pics/default.png')) }}')">
                                                <i class='bx bx-edit text-lg'></i> Edit
                                            </button>

                                            <button
                                                class="flex items-center gap-1 px-3 py-1.5 bg-cyan-500 text-white font-semibold rounded-lg shadow hover:bg-cyan-600 transition"
                                                onclick="openEmpAttendanceModal({{ $employee->id }}, '{{ $employee->user->name }}', '{{ $employee->emp_pic ? asset('storage/' . $employee->emp_pic) : asset('pics/default.png') }}')">
                                                <i class='bx bx-calendar-check text-lg'></i> View Attendance
                                            </button>

                                            <form method="POST" action="{{ route('employees.destroy', $employee->id) }}"
                                                onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="flex items-center gap-1 px-3 py-1.5 bg-red-500 text-white font-semibold rounded-lg shadow hover:bg-red-600 transition">
                                                    <i class='bx bx-trash text-lg'></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <div class="flex justify-center gap-2 flex-wrap">
                                            <button
                                                class="flex items-center gap-1 px-3 py-1.5 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition"
                                                onclick="openAttendanceModal('{{ $employee->user->email }}', '{{ $employee->user->name }}', '{{ $employee->emp_pic ? asset('storage/' . $employee->emp_pic) : asset('pics/default.png') }}', 'time-in')">
                                                <i class='bx bx-log-in'></i> Time In
                                            </button>

                                            <button
                                                class="flex items-center gap-1 px-3 py-1.5 bg-red-500 text-white font-semibold rounded-lg shadow hover:bg-red-600 transition"
                                                onclick="openAttendanceModal('{{ $employee->user->email }}', '{{ $employee->user->name }}', '{{ asset('storage/' . $employee->emp_pic) }}', 'time-out')">
                                                <i class='bx bx-log-out'></i> Time Out
                                            </button>
                                        </div>
                                    @endif
                                @endauth
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-gray-400 py-4 font-medium">No employees found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-center mt-6">
            <button class="px-3 py-1.5 border rounded-lg hover:bg-gray-100 transition">Previous</button>
            <span class="text-gray-700 font-medium">Page 1 of 1</span>
            <button class="px-3 py-1.5 border rounded-lg hover:bg-gray-100 transition">Next</button>
        </div>

    </div>

    @include('partials.passwordModal')
    @include('partials.updateEmpModal')
    @include('partials.EmpAttendanceModal')
    @include('partials.adminModal')
    @include('partials.addEmpModal')
    @include('partials.viewAttendanceSummary')

    <script>
        const timeInUrl = "{{ route('attendance.timeIn') }}";
        const timeOutUrl = "{{ route('attendance.timeOut') }}";
        const attendanceBase = "{{ url('/employees') }}";

        function openUpdateModal(id, userId, name, position, email, picUrl) {
            document.getElementById('update_id').value = id;
            document.getElementById('update_user_id').value = userId ?? '';
            document.getElementById('update_name').value = name ?? '';
            document.getElementById('update_position').value = position ?? '';
            document.getElementById('update_email').value = email ?? '';
            var preview = document.getElementById('update_emp_pic_preview');
            if (preview && picUrl) preview.src = picUrl;
            document.getElementById('updateModal').showModal();
        }

        function openEmpAttendanceModal(id, name, picUrl) {
            document.getElementById('attendanceEmpName').textContent = name ?? '';
            var preview = document.getElementById('attendanceEmpPic');
            if (preview && picUrl) preview.src = picUrl;

            const summary = document.getElementById('attendanceSummaryContent');
            summary.innerHTML = `<p class="text-center text-gray-400">Loading attendance...</p>`;

            fetch(`${attendanceBase}/${id}/attendance`)
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(payload => {
                    summary.innerHTML = '';
                    if (!payload.success) {
                        summary.innerHTML = `<p class="text-red-500">Unable to load attendance.</p>`;
                        return;
                    }
                    const data = payload.data || [];
                    if (data.length === 0) {
                        summary.innerHTML = `<p class="text-gray-500">No attendance records yet.</p>`;
                    } else {
                        // Build table
                        let table = `<table class="min-w-full border border-gray-300 rounded-lg">
                            <thead class="bg-blue-100">
                                <tr>
                                    <th class="px-3 py-2 border">Date</th>
                                    <th class="px-3 py-2 border">Time In</th>
                                    <th class="px-3 py-2 border">Time Out</th>
                                    <th class="px-3 py-2 border">Actions</th>
                                </tr>
                            </thead>
                            <tbody>`;
                        data.forEach(a => {
                            table += `<tr>
                                <td class="px-3 py-2 border">${a.date}</td>
                                <td class="px-3 py-2 border">${a.time_in ?? '-'}</td>
                                <td class="px-3 py-2 border">${a.time_out ?? '-'}</td>
                                <td class="px-3 py-2 border">
                                    <button class="btn btn-xs btn-warning" onclick="editAttendance(${a.id})">
                                        <i class="fas fa-edit"></i> Edit
                                </button>
                            </td>
                        </tr>`;
                        });
                        table += `</tbody></table>`;
                        summary.innerHTML = table;
                    }
                })
                .catch(err => {
                    summary.innerHTML = `<p class="text-red-500">Error loading attendance. Check console / logs.</p>`;
                    console.error('Attendance fetch error:', err);
                });

            document.getElementById('EmpAttendanceModal').showModal();
        }

        function openAttendanceModal(email, name, picUrl, actionType) {
            const form = document.getElementById('attendanceForm');
            document.getElementById('empEmailInput').value = email || '';
            document.getElementById('empNameDisplay').textContent = name || 'Employee Name';
            const pic = document.getElementById('empPicPreview');
            if (pic && picUrl) pic.src = picUrl;
            form.action = (actionType === 'time-out') ? timeOutUrl : timeInUrl;
            passwordDialog.showModal();
        }

        // Add this stub function for edit button (implement modal logic as needed)
        function editAttendance(attendanceId) {
            alert('Edit Attendance ID: ' + attendanceId);
            // You can open a modal here or redirect to an edit page
        }
    </script>
@endsection
