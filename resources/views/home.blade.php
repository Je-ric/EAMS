@extends('layouts.app')

@section('page-content')
    <div class="max-w-7xl mx-auto bg-white rounded-xl shadow-lg p-8">

        <!-- Header -->
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
                <!-- Search -->
                <div class="flex items-center border rounded-lg overflow-hidden">
                    <button class="px-3 py-2 text-gray-500 hover:bg-gray-100 transition"><i class='bx bx-search'></i></button>
                    <input type="text" id="searchInput" placeholder="Search here..."
                        class="flex-1 px-3 py-2 focus:outline-none">
                </div>

                <!-- Admin Buttons -->
                @auth
                    @if (Auth::user()->role === 'admin')
                        <button
                            class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                            onclick="attendanceSummaryModal.showModal()">
                            <i class='bx bx-clipboard'></i> Summary
                        </button>
                        <button
                            class="flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition"
                            onclick="addModal.showModal()">
                            <i class='bx bx-user-plus'></i> Add Employee
                        </button>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center gap-2 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                                <i class='bx bx-log-out'></i> Logout
                            </button>
                        </form>
                    @endif
                @else
                    <button
                        class="flex items-center gap-2 px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition"
                        onclick="document.getElementById('adminDialog').showModal()">
                        <i class='bx bx-shield'></i> Admin
                    </button>

                @endauth
            </div>
        </div>

        <!-- Employee Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-center border border-gray-200 rounded-lg table-auto">
                <thead class="bg-blue-600 text-white uppercase">
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">Profile</th>
                        <th class="px-4 py-2">Full Name</th>
                        <th class="px-4 py-2">Position</th>
                        <th class="px-4 py-2">Attendance</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $index => $employee)
                        <tr class="hover:bg-blue-50 transition-colors">
                            <td class="px-4 py-2">{{ $index + 1 }}</td>
                            <td class="px-4 py-2">
                                <img src="{{ $employee->emp_pic ? asset('storage/' . $employee->emp_pic) : 'https://via.placeholder.com/80x50' }}"
                                    alt="Profile"
                                    class="mx-auto rounded-full w-12 h-12 object-cover border border-gray-300">
                            </td>
                            <td class="px-4 py-2 font-semibold">{{ $employee->user->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2 text-gray-600">{{ $employee->position }}</td>
                            <td class="px-4 py-2 text-gray-700">
                                @php
                                    $last = $employee->attendances->first();
                                @endphp

                                @if ($last)
                                    {{ \Carbon\Carbon::parse($last->time_in, 'Asia/Manila')->format('h:i A') ?? '-' }} -
                                    @if ($last->time_out)
                                        {{ \Carbon\Carbon::parse($last->time_out, 'Asia/Manila')->format('h:i A') }}
                                    @else
                                        <span class="text-yellow-500 font-semibold">Active</span>
                                    @endif
                                @else
                                    <span class="text-red-500 font-semibold">No record</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                @auth
                                    @if (Auth::user()->role === 'admin')
                                        <div class="flex justify-center gap-2 flex-wrap">
                                            <button
                                                class="flex items-center gap-1 px-3 py-1.5 bg-yellow-400 text-white rounded-lg hover:bg-yellow-500 transition"
                                                onclick="openUpdateModal({{ $employee->id }}, {{ $employee->user->id ?? 'null' }}, '{{ addslashes($employee->user->name ?? '') }}', '{{ addslashes($employee->position) }}', '{{ addslashes($employee->user->email ?? '') }}', '{{ addslashes($employee->emp_pic ? asset('storage/' . $employee->emp_pic) : asset('pics/default.png')) }}')">
                                                <i class='bx bx-edit'></i> Edit
                                            </button>
                                            <a href="{{ route('employees.attendance.page', $employee->id) }}"
                                                class="flex items-center gap-1 px-3 py-1.5 bg-cyan-500 text-white rounded-lg hover:bg-cyan-600 transition">
                                                <i class='bx bx-calendar-check'></i> Attendances
                                            </a>
                                            <form method="POST" action="{{ route('employees.destroy', $employee->id) }}"
                                                onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="flex items-center gap-1 px-3 py-1.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                                                    <i class='bx bx-trash'></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @else
                                    <div class="flex justify-center gap-2 flex-wrap">
                                        <button
                                            class="flex items-center gap-1 px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:opacity-50"
                                            data-email="{{ $employee->user->email }}"
                                            onclick="openAttendanceModal('{{ $employee->user->email }}', '{{ $employee->user->name }}', '{{ $employee->emp_pic ? asset('storage/' . $employee->emp_pic) : asset('pics/default.png') }}', 'time-in')"
                                            @if (!$employee->timeInDone || $employee->bothDone) disabled @endif>
                                            <i class='bx bx-log-in'></i> Time In
                                        </button>

                                        <button
                                            class="flex items-center gap-1 px-3 py-1.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition disabled:opacity-50"
                                            data-email="{{ $employee->user->email }}"
                                            onclick="openAttendanceModal('{{ $employee->user->email }}', '{{ $employee->user->name }}', '{{ $employee->emp_pic ? asset('storage/' . $employee->emp_pic) : asset('pics/default.png') }}', 'time-out')"
                                            @if ($employee->timeInDone || $employee->timeOutDone || $employee->bothDone) disabled @endif>
                                            <i class='bx bx-log-out'></i> Time Out
                                        </button>
                                    </div>

                                @endauth
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-gray-400 py-4">No employees found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center mt-6">
            <button class="px-3 py-1.5 border rounded-lg hover:bg-gray-100 transition">Previous</button>
            <span class="text-gray-700 font-medium">Page 1 of 1</span>
            <button class="px-3 py-1.5 border rounded-lg hover:bg-gray-100 transition">Next</button>
        </div>

    </div>

    @include('partials.passwordModal')
    @include('partials.updateEmpModal')
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
            const preview = document.getElementById('update_emp_pic_preview');
            if (preview && picUrl) preview.src = picUrl;
            document.getElementById('updateModal').showModal();
        }

        function openAttendanceModal(email, name, picUrl, actionType) {
            const form = document.getElementById('attendanceForm');
            document.getElementById('empEmailInput').value = email || '';
            document.getElementById('empNameDisplay').textContent = name || 'Employee Name';
            const pic = document.getElementById('empPicPreview');
            if (pic && picUrl) pic.src = picUrl;
            form.action = actionType === 'time-out' ? timeOutUrl : timeInUrl;
            passwordDialog.showModal();
        }
    </script>
@endsection
