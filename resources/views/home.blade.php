@extends('layouts.app')

@section('page-content')
    <div class="max-w-7xl mx-auto bg-white rounded-xl shadow-lg p-8">

        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">
                    Employee Attendance
                </h1>
                <p class="text-gray-500 text-sm mt-1">
                    {{ \Carbon\Carbon::now('Asia/Manila')->format('F j, Y (l) • h:i A') }}
                </p>
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
                        <a href="{{ route('attendance.export.today') }}"
                            class="ml-2 inline-flex items-center gap-2 px-3 py-1.5 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                            <i class="bx bx-download"></i> Export CSV
                        </a>
                        <a href="{{ route('attendance.export.today.pdf') }}"
                            class="ml-2 inline-flex items-center gap-2 px-3 py-1.5 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                            <i class="bx bxs-file-pdf"></i> Export PDF
                        </a>
                    @endif
                @endauth

                @guest
                    <div>
                        <button
                            class="flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition"
                            onclick="registerEmployeeModal.showModal()">
                            <i class='bx bx-user-plus'></i> Register as Employee
                        </button>
                    </div>
                @endguest

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
                            <td class="px-4 py-2">{{ ($employees->firstItem() ?? 0) + $index }}</td>
                            <td class="px-4 py-2 text-center">
                                @if ($employee->emp_pic)
                                    <img src="{{ asset('storage/' . $employee->emp_pic) }}" alt="Profile"
                                        class="mx-auto rounded-full w-12 h-12 object-cover border border-gray-300">
                                @else
                                    <div
                                        class="mx-auto flex items-center justify-center w-12 h-12 rounded-full bg-blue-600 text-white text-lg font-bold border border-gray-300">
                                        {{ strtoupper(substr($employee->user->name ?? 'N', 0, 1)) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-2 font-semibold">
                                {{ $employee->user->name ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-2 text-gray-600">
                                @php
                                    $isNewRegistered =
                                        isset($employee->user->password) &&
                                        \Illuminate\Support\Facades\Hash::check(
                                            'password123',
                                            $employee->user->password,
                                        );
                                @endphp
                                @if ($isNewRegistered)
                                    <span
                                        class="ml-2 inline-block text-xs px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full">
                                        New — change password
                                    </span>
                                @else
                                    {{ $employee->position }}
                                @endif
                            </td>
                            <td class="px-4 py-2 text-gray-700">
                                @php
                                    $today = \Carbon\Carbon::today()->toDateString();
                                    $todayAttendance = $employee->attendances->firstWhere('date', $today);
                                @endphp

                                @if ($todayAttendance)
                                    {{ $todayAttendance->time_in ? \Carbon\Carbon::parse($todayAttendance->time_in, 'Asia/Manila')->format('h:i A') : '' }}
                                    @if ($todayAttendance->time_out)
                                        -
                                        {{ \Carbon\Carbon::parse($todayAttendance->time_out, 'Asia/Manila')->format('h:i A') }}
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
                                                onclick="openUpdateModal({{ $employee->id }}, {{ $employee->user->id ?? 'null' }}, '{{ addslashes($employee->user->name ?? '') }}', '{{ addslashes($employee->position) }}', '{{ addslashes($employee->user->email ?? '') }}', '{{ addslashes($employee->emp_pic ? asset('storage/' . $employee->emp_pic) : asset('pics/default.png')) }}', '{{ addslashes($employee->login_provider ?? '') }}')">
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
                                            @if ($employee->timeInDone || ($employee->timeInDone && $employee->timeOutDone)) disabled @endif>
                                            <i class='bx bx-log-in'></i> Time In
                                        </button>

                                        <button
                                            class="flex items-center gap-1 px-3 py-1.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition disabled:opacity-50"
                                            data-email="{{ $employee->user->email }}"
                                            onclick="openAttendanceModal('{{ $employee->user->email }}', '{{ $employee->user->name }}', '{{ $employee->emp_pic ? asset('storage/' . $employee->emp_pic) : asset('pics/default.png') }}', 'time-out')"
                                            @if (!$employee->timeInDone || $employee->timeOutDone) disabled @endif>
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

        {{ $employees->links('vendor.pagination.custom') }}


    </div>


    @include('partials.passwordModal')
    @include('partials.updateEmpModal')
    @include('partials.adminModal')
    @include('partials.addEmpModal')
    @include('partials.viewAttendanceSummary')
    @include('partials.registerEmpModal')

    <script>
        const timeInUrl = "{{ route('attendance.timeIn') }}";
        const timeOutUrl = "{{ route('attendance.timeOut') }}";
        const attendanceBase = "{{ url('/employees') }}";

        function openUpdateModal(id, userId, name, position, email, picUrl, loginProvider = '') {
            document.getElementById('update_id').value = id;
            document.getElementById('update_user_id').value = userId ?? '';
            document.getElementById('update_name').value = name ?? '';
            document.getElementById('update_position').value = position ?? '';
            const emailInput = document.getElementById('update_email');
            if (emailInput) {
                emailInput.value = email ?? '';
                const readOnly = loginProvider === 'google' || loginProvider === 'facebook';
                emailInput.readOnly = readOnly;
                // visual cue for readonly
                if (readOnly) {
                    emailInput.classList.add('bg-gray-100', 'cursor-not-allowed');
                } else {
                    emailInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
                }
            }
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


        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                const query = $(this).val();

                $.ajax({
                    url: "{{ route('employees.search') }}",
                    method: 'GET',
                    data: { query: query },
                    beforeSend: function() {
                        $('tbody').html('<tr><td colspan="6" class="py-4 text-gray-400">Searching...</td></tr>');
                    },
                    success: function(res) {
                        $('tbody').html(res.html);
                        $('.pagination').html(res.pagination);
                    },
                    error: function() {
                        $('tbody').html('<tr><td colspan="6" class="py-4 text-red-500">Failed to fetch results.</td></tr>');
                    }
                });
            });
        });

    </script>


@endsection
