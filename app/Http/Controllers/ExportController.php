<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ExportController extends Controller
{
    // Export all attendances for a single employee (CSV) - kung sino man viniew
    // Used by:
    //  - resources/views/EmpAttendance.blade.php (Export Attendance button)
    public function exportEmployee($id)
    {
        $employee = Employee::with('user')->findOrFail($id);

        $attendances = Attendance::where('emp_id', $employee->id)
            ->orderBy('date')
            ->get();

        $filename = 'attendance_employee_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', ($employee->user->name ?? 'emp_' . $id)) . '_' . now()->format('Ymd') . '.csv';

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
    // Used by:
    //  - resources/views/home.blade.php (Export Attendance button)
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




    public function exportEmployeePdf($id)
    {
        $employee = Employee::with('user', 'attendances')->findOrFail($id);
        $attendances = $employee->attendances()->orderBy('date')->get();

        $pdf = Pdf::loadView('pdf.employee_pdf', compact('employee', 'attendances'))
            ->setPaper('a4', 'portrait');

        $filename = 'attendance_employee_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', ($employee->user->name ?? 'emp_' . $id)) . '_' . now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    public function exportTodayPdf()
    {
        $today = now()->toDateString();
        $attendances = Attendance::where('date', $today)
            ->with('employee.user')
            ->orderBy('emp_id')
            ->get();

        $pdf = Pdf::loadView('pdf.today_pdf', compact('attendances', 'today'))
            ->setPaper('a4', 'landscape');

        $filename = 'attendance_today_' . now()->format('Ymd') . '.pdf';
        return $pdf->download($filename);
    }






    public function exportSummaryCsv(Request $request)
    {
        $from = Carbon::parse($request->from)->startOfDay()->toDateString();
        $to = Carbon::parse($request->to)->endOfDay()->toDateString();

        $records = DB::table('attendance')
            ->join('employees', 'attendance.emp_id', '=', 'employees.id')
            ->join('users', 'employees.user_id', '=', 'users.id')
            ->whereBetween('attendance.date', [$from, $to])
            ->select(
                'attendance.date',
                'users.name as employee',
                'employees.position',
                'attendance.time_in',
                'attendance.time_out',
            )
            ->orderBy('attendance.date', 'asc')
            ->orderBy('users.name', 'asc')
            ->get()
            ->groupBy('date');

        $filename = "attendance_summary_{$from}_to_{$to}.csv";

        header('Content-Type: text/csv');
        header("Content-Disposition: attachment; filename={$filename}");

        $handle = fopen('php://output', 'w');

        foreach ($records as $date => $rows) {
            fputcsv($handle, []);
            fputcsv($handle, ["Date: {$date}"]);
            fputcsv($handle, ['Employee', 'Position', 'Time In', 'Time Out']);

            foreach ($rows as $row) {
                $timeIn = $row->time_in ? Carbon::parse($row->time_in)->format('h:i A') : '-';
                $timeOut = $row->time_out ? Carbon::parse($row->time_out)->format('h:i A') : '-';

                fputcsv($handle, [
                    $row->employee,
                    $row->position,
                    $timeIn,
                    $timeOut,
                ]);
            }
        }

        fclose($handle);
        exit;
    }





    public function exportSummaryPdf(Request $request)
    {
        $from = Carbon::parse($request->from)->startOfDay()->toDateString();
        $to = Carbon::parse($request->to)->endOfDay()->toDateString();

        $records = DB::table('attendance')
            ->join('employees', 'attendance.emp_id', '=', 'employees.id')
            ->join('users', 'employees.user_id', '=', 'users.id')
            ->whereBetween('attendance.date', [$from, $to])
            ->select(
                'attendance.date',
                'users.name as employee',
                'employees.position',
                'attendance.time_in',
                'attendance.time_out',
            )
            ->orderBy('attendance.date', 'asc')
            ->orderBy('users.name', 'asc')
            ->get()
            ->groupBy('date');

        $pdf = Pdf::loadView('pdf.summary_pdf', [
            'records' => $records,
            'from' => $from,
            'to' => $to
        ])->setPaper('a4', 'landscape');

        $filename = "attendance_summary_{$from}_to_{$to}.pdf";
        return $pdf->download($filename);
    }
}
