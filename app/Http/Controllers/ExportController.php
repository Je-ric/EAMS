<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;

class ExportController extends Controller
{
    // Export all attendances for a single employee (CSV)
    public function exportEmployee($id)
    {
        $employee = Employee::with('user')->findOrFail($id);

        $attendances = Attendance::where('emp_id', $employee->id)
            ->orderBy('date')
            ->get();

        $filename = 'attendance_employee_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', ($employee->user->name ?? 'emp_'.$id)) . '_' . now()->format('Ymd') . '.csv';

        $response = new StreamedResponse(function () use ($attendances, $employee) {
            $handle = fopen('php://output', 'w');
            // header row
            fputcsv($handle, ['Date', 'Time In', 'Time Out', 'Employee Name', 'Position', 'Email']);

            foreach ($attendances as $a) {
                $date = $a->date;
                $timeIn = $a->time_in ? Carbon::parse($a->time_in)->format('h:i A') : '';
                $timeOut = $a->time_out ? Carbon::parse($a->time_out)->format('h:i A') : '';
                fputcsv($handle, [
                    $date,
                    $timeIn,
                    $timeOut,
                    $employee->user->name ?? '',
                    $employee->position ?? '',
                    $employee->user->email ?? '',
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }

    // Export all today's attendance records (CSV)
    public function exportToday()
    {
        $today = now()->toDateString();

        $attendances = Attendance::where('date', $today)
            ->with('employee.user')
            ->orderBy('emp_id')
            ->get();

        $filename = 'attendance_today_' . now()->format('Ymd') . '.csv';

        $response = new StreamedResponse(function () use ($attendances, $today) {
            $handle = fopen('php://output', 'w');
            // header row
            fputcsv($handle, ['Date', 'Employee ID', 'Employee Name', 'Position', 'Email', 'Time In', 'Time Out']);

            foreach ($attendances as $a) {
                $emp = $a->employee;
                $user = $emp->user ?? null;
                $timeIn = $a->time_in ? Carbon::parse($a->time_in)->format('h:i A') : '';
                $timeOut = $a->time_out ? Carbon::parse($a->time_out)->format('h:i A') : '';
                fputcsv($handle, [
                    $a->date,
                    $emp->id ?? '',
                    $user->name ?? '',
                    $emp->position ?? '',
                    $user->email ?? '',
                    $timeIn,
                    $timeOut,
                ]);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}