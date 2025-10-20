<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;

Route::get('/', [EmployeeController::class, 'index'])->name('index');

Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', function () {
        return view('home');
    })->name('admin.home');
});

Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
Route::put('/employees/update', [EmployeeController::class, 'update'])->name('employees.update');
Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');


Route::post('/attendance/time-in', [AttendanceController::class, 'timeIn'])->name('attendance.timeIn');
Route::post('/attendance/time-out', [AttendanceController::class, 'timeOut'])->name('attendance.timeOut');
