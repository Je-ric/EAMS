<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SocialAuthController;

Route::get('/', [EmployeeController::class, 'index'])->name('index');

Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
Route::put('/employees/update', [EmployeeController::class, 'update'])->name('employees.update');
Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
Route::get('/employees/{id}/attendance', [EmployeeController::class, 'getAttendance'])->name('employees.attendance');
Route::get('/employees/{id}/attendance-page', [EmployeeController::class, 'attendancePage'])->name('employees.attendance.page');

Route::post('/attendance/time-in', [AttendanceController::class, 'timeIn'])->name('attendance.timeIn');
Route::post('/attendance/time-out', [AttendanceController::class, 'timeOut'])->name('attendance.timeOut');
Route::put('/attendance/{id}', [AttendanceController::class, 'updateAttendance'])->name('attendance.update');


Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

Route::post('/employee/set-password', [EmployeeController::class, 'setPassword'])
    ->name('employee.setPassword');

//Route::get('/force-logout', function () {
//     Auth::logout();
//     session()->invalidate();
//     session()->regenerateToken();
//     return redirect()->route('index')->with('success', 'Logged out successfully.');
// });
