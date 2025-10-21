<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\User;

class AttendanceController extends Controller
{
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

        $attendance->time_in = now()->format('H:i:s'); // <-- important fix
        $attendance->save();

        return back()->with('success', 'Time-in recorded successfully.');
    }


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

        if ($attendance->time_in) {
            return back()->with('error', 'You already timed in today.');
        }

        $attendance->time_out = now()->format('H:i:s');

        $attendance->save();

        return back()->with('success', 'Time-in recorded successfully.');
    }


    public function updateAttendance(Request $request, $id)
{
    $request->validate([
        'time_in'  => 'nullable|date_format:H:i:s',
        'time_out' => 'nullable|date_format:H:i:s',
    ]);

    $attendance = Attendance::findOrFail($id);

    $attendance->update([
        'time_in'  => $request->time_in,
        'time_out' => $request->time_out,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Attendance updated successfully',
        'data' => $attendance
    ]);
}
}
