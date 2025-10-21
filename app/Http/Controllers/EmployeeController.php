<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['user', 'attendances'])->get();
        $today = Carbon::today()->toDateString();

        // Map each employee to include timeInDone and timeOutDone flags
        $employees = $employees->map(function ($employee) use ($today) {
            $todayAttendance = $employee->attendances->firstWhere('date', $today);
            $timeInDone = $todayAttendance && $todayAttendance->time_in ? true : false;
            $timeOutDone = $todayAttendance && $todayAttendance->time_out ? true : false;
            $bothDone = $timeInDone && $timeOutDone;
            $employee->timeInDone = $timeInDone;
            $employee->timeOutDone = $timeOutDone;
            $employee->bothDone = $bothDone;
            return $employee;
        });
        return view('home', compact('employees'));
    }

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

    public function getAttendance($id)
    {
        try {
            $employee = Employee::findOrFail($id);

            $attendance = $employee->attendances()
                ->limit(5)
                ->get(['date', 'time_in', 'time_out']);

            $data = $attendance->map(function ($a) {
                return [
                    'date'     => $a->date,
                    'time_in'  => $a->time_in ?: null,
                    'time_out' => $a->time_out ?: null,
                ];
            });

            return response()->json(['success' => true, 'data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Could not load attendance.'], 500);
        }
    }

    public function attendancePage($id, Request $request)
    {
        $employee = Employee::with('user')->findOrFail($id);

        // Get week start (Monday) and end (Sunday) for current page
        $week = $request->query('week', 0); // 0 = current week, 1 = previous, etc.
        $startOfWeek = now()->startOfWeek()->subWeeks($week);
        $endOfWeek = now()->endOfWeek()->subWeeks($week);

        $attendances = Attendance::where('emp_id', $employee->id)
            ->whereBetween('date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->orderBy('date', 'desc')
            ->get();

        // For pagination controls
        $firstAttendance = Attendance::where('emp_id', $employee->id)->orderBy('date')->first();
        $canPrev = $firstAttendance && $startOfWeek->gt(\Carbon\Carbon::parse($firstAttendance->date));

        return view('EmpAttendance', compact(
                    'employee',
                    'attendances',
                    'week',
                    'canPrev',
                    'startOfWeek',
                    'endOfWeek'));
    }
}
