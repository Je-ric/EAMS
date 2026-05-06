# EAMS — Future Works & Known Issues

This document tracks planned improvements, feature ideas, and known bugs for the Employee Attendance Management System.

---

## Table of Contents

1. [Critical Priority](#1-critical-priority)
2. [Normal Priority](#2-normal-priority)
3. [Low Priority](#3-low-priority)
4. [Known Bugs](#4-known-bugs)
5. [Technical Debt](#5-technical-debt)

---

## 1. Critical Priority

These are issues or missing features that directly affect correctness, security, or core usability.

---

### 1.1 No Authorization Middleware on Admin Routes

**Problem:** Employee management routes (`/employees`, `/attendance`, `/export`) have no middleware protecting them. Any unauthenticated user who knows the URL can POST to these routes.

**Fix:** Add `auth` and role-check middleware to all admin-only routes.

```php
// routes/web.php
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/employees', ...);
    Route::put('/employees/update', ...);
    Route::delete('/employees/{id}', ...);
    // etc.
});
```

**Impact:** Security vulnerability — currently unprotected.

---

### 1.2 No Duplicate Attendance Guard on storeAttendance

**Problem:** `storeAttendance()` uses `firstOrNew` but does not check if a record already exists before overwriting `time_in` and `time_out`. An admin can accidentally overwrite a valid attendance record.

**Fix:** Check if record exists and warn or require confirmation before overwriting.

---

### 1.3 Time Validation Allows Invalid Times

**Problem:** The regex `^\d{2}:\d{2}(:\d{2})?$` accepts `99:99:99` as valid. No semantic time validation is done.

**Fix:** Use Laravel's `date_format:H:i,H:i:s` validation rule instead of regex.

```php
'time_in' => 'nullable|date_format:H:i,H:i:s',
```

---

### 1.4 No CSRF Protection on AJAX Attendance Calls

**Problem:** AJAX calls for time-in/time-out may not consistently include the CSRF token depending on how the frontend is set up.

**Fix:** Ensure all AJAX POST/PUT requests include `X-CSRF-TOKEN` header or use `@csrf` in forms.

---

### 1.5 Employee Search Route Order Conflict

**Problem:** The route `GET /employees/search` is registered after `GET /employees/{id}/attendance-page`. Laravel may match `search` as an `{id}` parameter.

**Fix:** Register `/employees/search` **before** `/employees/{id}/...` routes in `web.php`.

---

## 2. Normal Priority

Features that would significantly improve the system but are not blocking.

---

### 2.1 Attendance Status: Absent / Late / Present

**Problem:** The system only records time-in and time-out. There is no concept of "absent," "late," or "present" status.

**Improvement:** Add a computed or stored `status` field:
- `present` — timed in on time
- `late` — timed in after a configurable cutoff (e.g., 9:00 AM)
- `absent` — no attendance record for a working day
- `half-day` — only time-in or only time-out

---

### 2.2 Working Hours Calculation

**Problem:** The system does not calculate total hours worked per day or per period.

**Improvement:** Add a computed `hours_worked` column or calculate it in the export/summary views.

```php
$hoursWorked = Carbon::parse($attendance->time_in)->diffInHours(Carbon::parse($attendance->time_out));
```

---

### 2.3 Pagination on Attendance History Page

**Problem:** `EmpAttendance.blade.php` loads all attendance records from the employee's first day to today. For long-tenured employees, this could be hundreds of rows.

**Improvement:** Add pagination or a date-range filter to the attendance history page.

---

### 2.4 Employee Profile Picture in Attendance Modal

**Problem:** The time-in/time-out modal shows only a text form. There is no visual confirmation of which employee is clocking in.

**Improvement:** Show the employee's profile picture and name in the modal after email is entered (via AJAX lookup).

---

### 2.5 Email Notifications

**Problem:** No email is sent when an employee is added, or when attendance is manually edited by an admin.

**Improvement:** Send email notifications using Laravel Mail:
- Welcome email when employee is created
- Notification when admin edits their attendance

---

### 2.6 Admin Dashboard with Statistics

**Problem:** The home page is just a table. There are no summary statistics visible at a glance.

**Improvement:** Add a stats bar at the top showing:
- Total employees
- Present today
- Absent today
- Late today

---

### 2.7 Bulk Import Employees via CSV

**Problem:** Employees must be added one by one.

**Improvement:** Add a CSV import feature so admins can upload a spreadsheet of employees.

---

### 2.8 Attendance Lock After Midnight

**Problem:** An employee could theoretically time in at 11:59 PM and time out the next day, creating a cross-day record.

**Improvement:** Lock attendance records at midnight. If time-out is not recorded by end of day, mark it as incomplete.

---

## 3. Low Priority

Nice-to-have features for future versions.

---

### 3.1 Dark Mode

Add a dark mode toggle using Tailwind's `dark:` classes.

---

### 3.2 Employee Self-Service Portal

Allow employees to log in and view their own attendance history without admin access.

---

### 3.3 Leave Management

Add a leave request system where employees can file absences and admins can approve or reject them.

---

### 3.4 Overtime Tracking

Track hours worked beyond the standard shift and flag them in reports.

---

### 3.5 Mobile-Responsive Attendance Kiosk Mode

Create a dedicated kiosk view (full-screen, tablet-friendly) for a shared device where employees can time in/out.

---

### 3.6 Audit Log

Track who made changes to attendance records and when (admin accountability).

---

### 3.7 Multiple Shifts Support

Allow configuring different shift schedules (morning, afternoon, night) per employee or department.

---

## 4. Known Bugs

---

### Bug 1: Search Route May Match as Employee ID

**Symptom:** Navigating to `/employees/search` may throw a model-not-found error because Laravel matches `search` as the `{id}` parameter.

**Root Cause:** Route order in `web.php` — `/employees/search` is registered after `/employees/{id}/attendance-page`.

**Workaround:** Register the search route first.

**Status:** Not yet fixed.

---

### Bug 2: exportSummaryCsv Uses `header()` + `exit` Instead of StreamedResponse

**Symptom:** `exportSummaryCsv()` uses raw PHP `header()` and `exit` instead of Laravel's `StreamedResponse`. This bypasses Laravel's response pipeline and may cause issues with middleware or session handling.

**Fix:** Refactor to use `StreamedResponse` like the other export methods.

**Status:** Not yet fixed.

---

### Bug 3: Employee Deletion Does Not Delete Attendance Records

**Symptom:** When an employee is deleted, their attendance records remain in the `attendance` table as orphaned rows (no FK cascade defined in migration).

**Fix:** Add `onDelete('cascade')` to the `emp_id` foreign key in the attendance migration, or manually delete attendance records in `EmployeeController@destroy`.

**Status:** Not yet fixed.

---

### Bug 4: Social Login Creates Employee Role, Not Linked to Employee Record

**Symptom:** When a user logs in via Google/Facebook for the first time, a `User` record is created with `role = employee`, but no corresponding `Employee` record is created. This means the user cannot time in/out.

**Fix:** After `findOrCreateSocialUser()`, check if an `Employee` record exists for that user and create one if not.

**Status:** Not yet fixed.

---

### Bug 5: Time In/Out Modal Does Not Clear on Error

**Symptom:** If time-in fails (wrong password, already timed in), the modal closes and the error is shown as a flash message. The user must reopen the modal to try again.

**Fix:** Keep the modal open on error and display the error inside the modal.

**Status:** Not yet fixed.

---

## 5. Technical Debt

---

### 5.1 No Form Request Classes

All validation is done inline in controllers. Should be extracted to dedicated `FormRequest` classes for cleaner code.

---

### 5.2 No Service Layer

Business logic (e.g., finding or creating social users, computing attendance status) is mixed into controllers. Should be extracted to service classes.

---

### 5.3 No Tests

The project has no feature or unit tests. Critical flows (time-in, time-out, employee CRUD) should have test coverage.

---

### 5.4 Commented-Out Code

Several blocks of commented-out code exist in `EmployeeController.php` and `routes/web.php`. These should be removed or properly documented.

---

### 5.5 SQLite in Production Risk

The project uses SQLite by default. SQLite is not suitable for concurrent production use. Should be migrated to MySQL or PostgreSQL before deployment.
