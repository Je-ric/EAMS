<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    /**
     * Display all employees with user data.
     */
    public function index()
    {
        $employees = Employee::with('user')->get();
        return view('home', compact('employees'));
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6',
            'position'  => 'required|string|max:100',
            'emp_pic'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle picture upload
        $picturePath = null;
        if ($request->hasFile('emp_pic')) {
            $picturePath = $request->file('emp_pic')->store('employees', 'public');
        }

        // Create linked user account
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'employee',
        ]);

        // Create employee record linked to user
        Employee::create([
            'user_id'   => $user->id,
            'position'  => $request->position,
            'emp_pic'   => $picturePath,
        ]);

        return back()->with('success', 'Employee added successfully.');
    }

    /**
     * Update an existing employee.
     */
    public function update(Request $request)
    {
        $request->validate([
            'id'        => 'required|exists:employees,id',
            'user_id'   => 'required|exists:users,id',
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $request->user_id,
            'password'  => 'nullable|min:6',
            'position'  => 'required|string|max:100',
            'emp_pic'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $employee = Employee::findOrFail($request->id);
        $user = User::findOrFail($request->user_id);

        // Handle profile picture update
        if ($request->hasFile('emp_pic')) {
            if ($employee->emp_pic && Storage::disk('public')->exists($employee->emp_pic)) {
                Storage::disk('public')->delete($employee->emp_pic);
            }
            $employee->emp_pic = $request->file('emp_pic')->store('employees', 'public');
        }

        // Update linked user
        $user->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->filled('password')
                ? Hash::make($request->password)
                : $user->password,
        ]);

        // Update employee details
        $employee->update([
            'position' => $request->position,
            'emp_pic'  => $employee->emp_pic,
        ]);

        return back()->with('success', 'Employee updated successfully.');
    }

    /**
     * Delete an employee (and linked user).
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $user = $employee->user;

        // Delete stored picture if exists
        if ($employee->emp_pic && Storage::disk('public')->exists($employee->emp_pic)) {
            Storage::disk('public')->delete($employee->emp_pic);
        }

        $employee->delete();
        if ($user) {
            $user->delete();
        }

        return back()->with('success', 'Employee deleted successfully.');
    }
}
