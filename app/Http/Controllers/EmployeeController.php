<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class EmployeeController extends Controller
{
    // Used by:
    //  - resources/views/home.blade.php (Employee Management Table)
    public function index()
    {
        $employees = Employee::with(['user', 'attendances'])->paginate(perPage: 5);
        $today = now()->toDateString();

        $employees->getCollection()->transform(function ($employee) use ($today) {
            $todayAttendance = $employee->attendances->firstWhere('date', $today);
            $employee->timeInDone = $todayAttendance && $todayAttendance->time_in ? true : false;
            $employee->timeOutDone = $todayAttendance && $todayAttendance->time_out ? true : false;
            return $employee;
        });

        return view('home', compact('employees'));
    }

    // Used by:
    //  - resources/views/home.blade.php (admin only)
    //  - resources/views/partials/employeeModal.blade.php (Add Employee form submits here)

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

    // Used by:
    //  - resources/views/home.blade.php (admin only)
    //  - resources/views/partials/employeeModal.blade.php (Edit Employee form submits here)

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

    // Used by:
    //  - resources/views/home.blade.php (admin only)
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


    // Used by:
    //  - resources/views/EmpAttendance.blade.php
    public function attendancePage($id, Request $request)
    {
        $employee = Employee::with('user')->findOrFail($id);

        // determine first attendance date (attendance) or fallback to employee created_at
        $firstAttendanceDate = Attendance::where('emp_id', $employee->id)
            ->orderBy('date')
            ->value('date');

        if ($firstAttendanceDate) {
            $startDate = Carbon::parse($firstAttendanceDate)->startOfDay();
        } else {
            $startDate = $employee->created_at ? Carbon::parse($employee->created_at)->startOfDay() : Carbon::now()->startOfDay();
        }

        // end date = today (no future dates)
        $endDate = Carbon::now()->startOfDay();

        // guard: if start is after today, clamp to today
        // means wala pang attendance record
        if ($startDate->gt($endDate)) {
            $startDate = $endDate;
        }

        // build date range from startDate to today (inclusive)
        $period = CarbonPeriod::create($startDate, '1 day', $endDate);
        $weekDates = collect($period)->values(); // reuse $weekDates variable used by the view

        // load attendances for this date range and key by date (Y-m-d)
        $attendances = Attendance::where('emp_id', $employee->id)
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->date)->toDateString();
            });

        // pass start/end for header display
        return view('EmpAttendance', compact('employee', 'weekDates', 'attendances'))
            ->with('startDate', $startDate)
            ->with('endDate', $endDate);
    }


    // Used by:
    //  - AJAX /fetch from (search - home.blade.php)
    public function search(Request $request)
    {
        $query = $request->get('query', '');

        $employees = Employee::with(['user', 'attendances'])
            ->whereHas('user', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%");
            })
            ->orWhere('position', 'like', "%{$query}%")
            ->paginate(5);

        $today = now()->toDateString();

        $employees->getCollection()->transform(function ($employee) use ($today) {
            $todayAttendance = $employee->attendances->firstWhere('date', $today);
            $employee->timeInDone = $todayAttendance && $todayAttendance->time_in ? true : false;
            $employee->timeOutDone = $todayAttendance && $todayAttendance->time_out ? true : false;
            return $employee;
        });

        // Return partial view (for table body)
        // may comment din sa employeeTableRows.blade.php
        // so every search may update sa UI, every search nagrerender ng dalawang view na toh, focus sa table
        // again, ginagawa ito para magupdate yung table at pagination dynamically, at hindi na magreload buong page
        // and kung mapapansin niyo, similar lang yung structure nito sa index() method above,
        // kase by default, index() yung pang-display, we use search() para mag-filter ng output,
        // additional lang yung query filter and rendering
        return response()->json([
            'html' => view('partials.employeeTableRows', compact('employees'))->render(), // 1
            'pagination' => view('vendor.pagination.custom', ['paginator' => $employees])->render() // 2
        ]);
    }


}
