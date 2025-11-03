<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\ExportController;

Route::get('/', [EmployeeController::class, 'index'])->name('index');

Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
Route::put('/employees/update', [EmployeeController::class, 'update'])->name('employees.update');
Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
Route::get('/employees/{id}/attendance-page', [EmployeeController::class, 'attendancePage'])->name('employees.attendance.page');
Route::get('/employees/search', [EmployeeController::class, 'search'])->name('employees.search');


Route::post('/attendance/time-in', [AttendanceController::class, 'timeIn'])->name('attendance.timeIn');
Route::post('/attendance/time-out', [AttendanceController::class, 'timeOut'])->name('attendance.timeOut');
Route::put('/attendance/{id}', [AttendanceController::class, 'updateAttendance'])->name('attendance.update');
Route::post('/attendance', [AttendanceController::class, 'storeAttendance'])->name('attendance.store');
Route::get('/attendance/summary', [AttendanceController::class, 'summary'])->name('attendance.summary');


Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

Route::get('/auth/facebook', [SocialAuthController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('/auth/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback'])->name('auth.facebook.callback');

// Route::post('/employee/set-password', [EmployeeController::class, 'setPassword'])
//     ->name('employee.setPassword');

//Route::get('/force-logout', function () {
//     Auth::logout();
//     session()->invalidate();
//     session()->regenerateToken();
//     return redirect()->route('index')->with('success', 'Logged out successfully.');
// });


Route::get('/employees/{id}/attendance/export', [ExportController::class, 'exportEmployee'])->name('employees.attendance.export');
Route::get('/attendance/export/today', [ExportController::class, 'exportToday'])->name('attendance.export.today');

Route::get('/employees/{id}/attendance/export-pdf', [ExportController::class, 'exportEmployeePdf'])->name('employees.attendance.export.pdf');
Route::get('/attendance/export/today-pdf', [ExportController::class, 'exportTodayPdf'])->name('attendance.export.today.pdf');

Route::get('/export-summary/csv', [ExportController::class, 'exportSummaryCsv'])->name('export.summary.csv');
Route::get('/export-summary/pdf', [ExportController::class, 'exportSummaryPdf'])->name('export.summary.pdf');
