@extends('layouts.app')

@section('page-content')
<div class="container mx-auto my-12 p-8 bg-white shadow-xl rounded-2xl border border-gray-200">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Employee Attendance</h1>
        <div class="flex gap-3 items-center w-full md:w-auto">
            <div class="relative w-full md:w-64">
                <input type="text" placeholder="Search employees..."
                    class="input input-bordered w-full pr-12 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition">
                <i class="fas fa-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
            <button class="btn btn-primary gap-2 shadow-lg hover:scale-105 transition-transform"
                onclick="document.getElementById('adminDialog').showModal()">
                <i class="fas fa-user-shield"></i> Admin
            </button>
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
                    <th>Attendance</th>
                </tr>
            </thead>
            <tbody>
                <tr class="hover:bg-blue-50 transition-colors">
                    <td class="font-medium text-gray-700">1</td>
                    <td><img src="https://via.placeholder.com/80x50" alt="Profile"
                            class="mx-auto rounded-full border border-gray-300"></td>
                    <td class="text-gray-800 font-semibold">John Doe</td>
                    <td class="text-gray-600">Manager</td>
                    <td class="flex justify-center gap-2">
                        <button class="btn btn-success btn-sm hover:bg-green-600">Time In</button>
                        <button class="btn btn-error btn-sm hover:bg-red-600">Time Out</button>
                    </td>
                </tr>
                <tr class="hover:bg-blue-50 transition-colors">
                    <td class="font-medium text-gray-700">2</td>
                    <td><img src="https://via.placeholder.com/80x50" alt="Profile"
                            class="mx-auto rounded-full border border-gray-300"></td>
                    <td class="text-gray-800 font-semibold">Jane Smith</td>
                    <td class="text-gray-600">Developer</td>
                    <td class="flex justify-center gap-2">
                        <button class="btn btn-success btn-sm hover:bg-green-600">Time In</button>
                        <button class="btn btn-error btn-sm hover:bg-red-600">Time Out</button>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="text-gray-400 py-4 font-medium">No more records</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center mt-6">
        <button class="btn btn-outline btn-sm hover:bg-gray-100">Previous</button>
        <span class="text-gray-700 font-medium">Page 1 of 1</span>
        <button class="btn btn-outline btn-sm hover:bg-gray-100">Next</button>
    </div>
</div>

@include('partials.employeeModal')
@include('partials.adminModal')
@endsection
