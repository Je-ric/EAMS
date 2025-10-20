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
                        @if (Auth::user()->role === 'admin')
                            <th>Actions</th>
                        @else
                            <th>Actions</th>
                        @endif
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

                                            <form method="POST" action="{{ route('employees.destroy', $employee->id) }}"
                                                onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-error btn-sm hover:bg-red-600">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <div class="flex justify-center gap-2">
                                            <button class="btn btn-success btn-sm hover:bg-green-600"
                                                onclick="passwordDialog.showModal()">
                                                <i class="fas fa-sign-in-alt"></i> Time In
                                            </button>
                                            <button class="btn btn-error btn-sm hover:bg-red-600"
                                                onclick="passwordDialog.showModal()">
                                                <i class="fas fa-sign-out-alt"></i> Time Out
                                            </button>
                                        </div>
                                    @endif
                                @endauth
                            </td>
                        </tr>

                        @include('partials.passwordModal')
                        @include('partials.updateEmpModal')
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

    @include('partials.adminModal')
    @include('partials.addEmpModal')
    @include('partials.viewAttendanceSummary')
    @include('partials.updateEmpModal')

    <script>
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
</script>

@endsection
