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

        $today = Carbon::today();

        $attendance = Attendance::firstOrNew([
            'emp_id' => $employee->id,
            'date' => $today,
        ]);

        if ($attendance->time_in) {
            return back()->with('error', 'You already timed in today.');
        }

        $attendance->time_in = now();
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

        $attendance = Attendance::where('emp_id', $employee->id)
            ->whereDate('date', Carbon::today())
            ->first();

        if (!$attendance || !$attendance->time_in) {
            return back()->with('error', 'You must time in before timing out.');
        }

        if ($attendance->time_out) {
            return back()->with('error', 'You already timed out today.');
        }

        $attendance->time_out = now();
        $attendance->save();

        return back()->with('success', 'Time-out recorded successfully.');
    }
}
