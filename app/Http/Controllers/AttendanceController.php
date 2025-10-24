<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\User;

class AttendanceController extends Controller
{
    
    // Used by:
    //  - resources/views/home.blade.php (Time In button opens modal -> submits here)
    //  - resources/views/partials/passwordModal.blade.php (attendance modal submit for time-in)

    public function timeIn(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Invalid credentials.');
        }

        $employee = Employee::where('user_id', $user->id)->first();
        if (!$employee) {
            return back()->with('error', 'Employee not found.');
        }

        $today = now()->toDateString();

        $attendance = Attendance::firstOrNew([
            'emp_id' => $employee->id,
            'date' => $today,
        ]);

        if ($attendance->time_in) {
            return back()->with('error', 'You already timed in today.');
        }

        // Store in 24-hour format, but viwe in 12-hour format
        $attendance->time_in = now()->format('H:i:s');
        $attendance->save();

        if ($request->ajax()) {
            return response()->json(['success' => 'Time-in recorded successfully!']);
        }
        return back()->with('success', 'Time-in recorded successfully.');
    }

    
    // Used by:
    //  - resources/views/home.blade.php (Time Out button opens modal -> submits here)
    //  - resources/views/partials/passwordModal.blade.php (attendance modal submit for time-out)
    
    public function timeOut(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Invalid credentials.');
        }

        $employee = Employee::where('user_id', $user->id)->first();
        if (!$employee) {
            return back()->with('error', 'Employee not found.');
        }

        $today = now()->toDateString();

        $attendance = Attendance::firstOrNew([
            'emp_id' => $employee->id,
            'date' => $today,
        ]);

        if (!$attendance->time_in) {
            return back()->with('error', 'You cannot time out before timing in.');
        }

        if ($attendance->time_out) {
            return back()->with('error', 'You already timed out today.');
        }

        // same
        $attendance->time_out = now()->format('H:i:s');
        $attendance->save();

        if ($request->ajax()) {
            return response()->json(['success' => 'Time-out recorded successfully!']);
        }
        return back()->with('success', 'Time-out recorded successfully.');
    }

    
    // Update an existing attendance record
    // Used by:
    //  - resources/views/EmpAttendance.blade.php (Edit Attendance modal -> submits PUT to update)
    
    public function updateAttendance(Request $request, $id)
    {
        $request->validate([
            'time_in'  => ['nullable', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'time_out' => ['nullable', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
        ]);

        $attendance = Attendance::findOrFail($id);

        // Convert 12-hour format to 24-hour for DB storage
        $timeIn = $request->time_in ? date('H:i:s', strtotime($request->time_in)) : null;
        $timeOut = $request->time_out ? date('H:i:s', strtotime($request->time_out)) : null;

        $attendance->update([
            'time_in'  => $timeIn,
            'time_out' => $timeOut,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attendance updated successfully',
            'data' => $attendance
        ]);
    }

    
    // create attendance (admin add for missed date) — no password required
    // Used by:
    //  - resources/views/EmpAttendance.blade.php (Add Attendance button -> opens modal -> submits POST)
    //  - AJAX /fetch from EmpAttendance page to create missed-day records without password
    
    public function storeAttendance(Request $request)
    {
        $request->validate([
            'emp_id'   => 'required|exists:employees,id',
            'date'     => 'required|date|before_or_equal:today',
            'time_in'  => ['nullable','regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'time_out' => ['nullable','regex:/^\d{2}:\d{2}(:\d{2})?$/'],
        ]);

        // convert times to H:i:s
        $timeIn = $request->time_in ? date('H:i:s', strtotime($request->time_in)) : null;
        $timeOut = $request->time_out ? date('H:i:s', strtotime($request->time_out)) : null;

        $attendance = Attendance::firstOrNew([
            'emp_id' => $request->emp_id,
            'date' => $request->date,
        ]);

        $attendance->time_in = $timeIn;
        $attendance->time_out = $timeOut;
        $attendance->save();

        return response()->json([
            'success' => true,
            'message' => 'Attendance saved successfully',
            'data' => $attendance
        ]);
    }
}
