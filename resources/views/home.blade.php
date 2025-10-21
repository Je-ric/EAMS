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

                <div class="search-bar">
                    <button class="search-btn"><i class="fas fa-search"></i></button>
                    <input type="text" id="searchInput" placeholder="Search here...">
                </div>
                @auth
                    @if (Auth::user()->role === 'admin')
                        <button class="btn btn-primary" onclick="attendanceSummaryModal.showModal()">
                            <i class="fas fa-clipboard-list"></i> View Attendance Summary
                        </button>
                        <button class="btn btn-success gap-2" onclick="addModal.showModal()">
                            <i class="fas fa-user-plus"></i> Add Employee
                        </button>

                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-error gap-2 shadow-lg hover:scale-105 transition-transform">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    @endif
                @else
                    <button class="btn btn-primary gap-2 shadow-lg hover:scale-105 transition-transform"
                        onclick="document.getElementById('adminDialog').showModal()">
                        <i class="fas fa-user-shield"></i> Admin
                    </button>
                @endauth
            </div>
        </div>


        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="table table-zebra table-hover w-full text-center border border-gray-200 rounded-lg">
                <thead class="bg-gradient-to-r from-blue-600 to-blue-500 text-white text-lg uppercase">
                    <tr>
                        <th>#</th>
                        <th>Profile</th>
                        <th>Full Name</th>
                        <th>Position</th>
                        @auth
                            @if (Auth::user()->role === 'admin')
                                <th>Actions</th>
                            @endif
                        @else
                            <th>Actions</th>
                        @endauth
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $index => $employee)
                        <tr class="hover:bg-blue-50 transition-colors">
                            <td class="font-medium text-gray-700">{{ $index + 1 }}</td>
                            <td>
                                <img src="{{ $employee->emp_pic ? asset('storage/' . $employee->emp_pic) : 'https://via.placeholder.com/80x50' }}"
                                    alt="Profile"
                                    class="mx-auto rounded-full border border-gray-300 w-12 h-12 object-cover">
                            </td>
                            <td class="text-gray-800 font-semibold">{{ $employee->user->name ?? 'N/A' }}</td>
                            <td class="text-gray-600">{{ $employee->position }}</td>

                            <td>
                                @auth
                                    @if (Auth::user()->role === 'admin')
                                        <div class="flex justify-center gap-2">
                                            <button class="btn btn-warning btn-sm"
                                                onclick="openUpdateModal({{ $employee->id }}, {{ $employee->user->id ?? 'null' }},
                                                                    '{{ addslashes($employee->user->name ?? '') }}',
                                                                    '{{ addslashes($employee->position) }}',
                                                                    '{{ addslashes($employee->user->email ?? '') }}',
                                                                    '{{ addslashes($employee->emp_pic ? asset('storage/' . $employee->emp_pic) : asset('pics/default.png')) }}')">
                                                <i class="fas fa-user-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-info btn-sm" 
                                                    onclick="openEmpAttendanceModal({{ $employee->id }}, '{{ $employee->user->name }}', '{{ $employee->emp_pic ? asset('storage/' . $employee->emp_pic) : asset('pics/default.png') }}')">
                                                    <i class="fas fa-calendar-check"></i> View Attendance
                                            </button>

                                            <form method="POST" action="{{ route('employees.destroy', $employee->id) }}"
                                                onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-error btn-sm hover:bg-red-600">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @else
                                    <button
                                        class="btn btn-primary flex items-center gap-2 px-4 py-2 rounded-lg shadow hover:scale-105 transition-transform"
                                        onclick="openAttendanceModal('{{ $employee->user->email }}', '{{ $employee->user->name }}', '{{ $employee->emp_pic ? asset('storage/' . $employee->emp_pic) : asset('pics/default.png') }}', 'time-in')">
                                        <i class="fas fa-sign-in-alt"></i>
                                        <span>Time In</span>
                                    </button>

                                    <!-- Time Out Button -->
                                    <button
                                        class="btn btn-error flex items-center gap-2 px-4 py-2 rounded-lg shadow hover:scale-105 transition-transform"
                                        onclick="openAttendanceModal('{{ $employee->user->email }}', '{{ $employee->user->name }}', '{{ asset('storage/' . $employee->emp_pic) }}', 'time-out')">
                                        <i class="fas fa-sign-out-alt"></i>
                                        <span>Time Out</span>
                                    </button>
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
            <button class="btn btn-outline btn-sm hover:bg-gray-100">Previous</button>
            <span class="text-gray-700 font-medium">Page 1 of 1</span>
            <button class="btn btn-outline btn-sm hover:bg-gray-100">Next</button>
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
            // Set employee info
            document.getElementById('attendanceEmpName').textContent = name ?? '';
            var preview = document.getElementById('attendanceEmpPic');
            if (preview && picUrl) preview.src = picUrl;

            // Show loading
            const summary = document.getElementById('attendanceSummaryContent');
            summary.innerHTML = `<p class="text-center text-gray-400">Loading attendance...</p>`;

            // Fetch attendance
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
                        data.forEach(a => {
                            const row = document.createElement('div');
                            row.className = 'mb-2 text-left';
                            row.innerHTML = `
                                <p><strong>Date:</strong> ${a.date}</p>
                                <p><strong>Time In:</strong> ${a.time_in ?? '-'}</p>
                                <p><strong>Time Out:</strong> ${a.time_out ?? '-'}</p>
                                <hr class="my-1"/>
                            `;
                            summary.appendChild(row);
                        });
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
    </script>
@endsection
