{{--
ito naman ay to render paginated employee table rows with actions
mainly for search results and home page, every search na ginagawa ay nagrerequest sa EmployeeController
to display specific employees by rendering this partial view. Imagine, instead mag page reload,
nirereload/nirerender lang natin yung blade na ito mismo, para makapagdisplay ng updated employee list. --}}

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
            {{ $employee->position }}
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

                {{-- Data attributes:
                - data-email="{{ $employee->user->email }}" ginagamit ito sa JS para mahanap yung specific employee row
                    pagkatapos ng AJAX success, para ma-update agad yung UI ng row na iyon lang.
                    Since partial view lang ang nire-render (hindi buong page reload), ginagamit natin ang email
                    bilang unique identifier para maiwasan ang pagkakamali sa pag-update ng tamang row.
                    Example:
                        With data-email → madaling i-locate at i-update gamit ang email (unique)
                        Without data-email → kailangan mag-loop at maghanap sa table, posibleng magkamali kung may duplicate names
                --}}
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
        <td colspan="6" class="text-gray-400 py-4">No employees found</td>
    </tr>
@endforelse
