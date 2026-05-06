# EAMS — How It Works

Complete system flow documentation covering authentication, employee management, attendance tracking, and exports.

---

## Table of Contents

1. [System Overview](#1-system-overview)
2. [Authentication Flow](#2-authentication-flow)
3. [Employee Management](#3-employee-management)
4. [Attendance Flow](#4-attendance-flow)
5. [Attendance History Page](#5-attendance-history-page)
6. [Export System](#6-export-system)
7. [Social Login Flow](#7-social-login-flow)
8. [Database Structure](#8-database-structure)
9. [Route Reference](#9-route-reference)

---

## 1. System Overview

```
[Home Page /]
    │
    ├── Employee Table (paginated, searchable)
    │       └── Each row: Name, Position, Email, Today's Status, Actions
    │
    ├── Admin Panel (top-right, only visible when logged in as admin)
    │       ├── Add Employee
    │       ├── Export Today's Attendance (CSV / PDF)
    │       └── Attendance Summary (date range → CSV / PDF)
    │
    ├── Time In / Time Out Buttons (per employee row)
    │       └── Opens password modal → employee enters credentials → records attendance
    │
    └── View Attendance Button (per employee row)
            └── Opens /employees/{id}/attendance-page
```

---

## 2. Authentication Flow

### 2.1 Admin Login

Only users with `role = admin` can log in to the admin panel.

```
[Admin Login Modal]
    │
    POST /admin/login → AuthController@login
    │
    ├── Validate email + password
    ├── Auth::attempt() → check credentials
    ├── If role !== 'admin' → logout + error "Admins only"
    └── If valid → redirect to home (index route)
```

**Key rule:** The login is admin-only. Regular employees do not log in to the web session — they only authenticate via the time-in/time-out password modal.

### 2.2 Admin Logout

```
POST /admin/logout → AuthController@logout
    │
    ├── Auth::logout()
    ├── session()->invalidate()
    ├── session()->regenerateToken()
    └── Redirect to home
```

---

## 3. Employee Management

All employee management actions are admin-only.

### 3.1 View Employees

```
GET / → EmployeeController@index
    │
    ├── Employee::with(['user', 'attendances'])->paginate(5)
    ├── For each employee: check today's attendance
    │       ├── timeInDone = true if time_in exists for today
    │       └── timeOutDone = true if time_out exists for today
    └── Return home.blade.php with $employees
```

### 3.2 Add Employee

```
POST /employees → EmployeeController@store
    │
    ├── Validate: name, email (unique), password, position, emp_pic (optional)
    ├── Handle image upload → store in storage/app/public/employees/
    ├── Create User record (role = 'employee')
    ├── Create Employee record linked to user_id
    └── Redirect back with success message
```

**Storage note:** Images are stored with a timestamped filename to avoid collisions. Run `php artisan storage:link` to expose public URLs.

### 3.3 Update Employee

```
PUT /employees/update → EmployeeController@update
    │
    ├── Validate: id, user_id, name, email, password (nullable), position, emp_pic
    ├── If new image uploaded → delete old image from storage, store new one
    ├── Update User record (name, email, password if provided)
    ├── Update Employee record (position, emp_pic)
    └── Redirect back with success message
```

### 3.4 Delete Employee

```
DELETE /employees/{id} → EmployeeController@destroy
    │
    ├── Find Employee by ID
    ├── Delete stored profile picture if exists
    ├── Delete Employee record (cascades attendance via DB)
    ├── Delete linked User record
    └── Redirect back with success message
```

### 3.5 Search Employees (AJAX)

```
GET /employees/search?query=... → EmployeeController@search
    │
    ├── Filter by name, email, or position (LIKE query)
    ├── Paginate results (5 per page)
    ├── Add timeInDone / timeOutDone flags
    └── Return JSON:
            ├── html → rendered partials/employeeTableRows.blade.php
            └── pagination → rendered vendor/pagination/custom.blade.php
```

The home page JavaScript intercepts the search input, sends AJAX requests, and replaces the table body and pagination HTML dynamically without a full page reload.

---

## 4. Attendance Flow

### 4.1 Time In

```
POST /attendance/time-in → AttendanceController@timeIn
    │
    ├── Validate email + password
    ├── Find User by email → verify password with Hash::check()
    ├── Find Employee linked to that user
    ├── Check today's attendance record (firstOrNew by emp_id + date)
    ├── If time_in already exists → error "Already timed in today"
    ├── Set time_in = now() in H:i:s (24-hour format)
    ├── Save attendance record
    └── Return success (JSON if AJAX, redirect if standard)
```

**Time format:** Stored as 24-hour (`H:i:s`) in the database. Displayed as 12-hour (`h:i A`) in views and exports.

### 4.2 Time Out

```
POST /attendance/time-out → AttendanceController@timeOut
    │
    ├── Same credential validation as Time In
    ├── Check today's attendance record
    ├── If time_in is null → error "Cannot time out before timing in"
    ├── If time_out already exists → error "Already timed out today"
    ├── Set time_out = now() in H:i:s
    ├── Save attendance record
    └── Return success
```

### 4.3 Admin: Edit Attendance

```
PUT /attendance/{id} → AttendanceController@updateAttendance
    │
    ├── Validate time_in and time_out (regex: HH:MM or HH:MM:SS)
    ├── Convert input to H:i:s format
    ├── Update attendance record
    └── Return JSON with updated data
```

### 4.4 Admin: Add Attendance (Missed Day)

```
POST /attendance → AttendanceController@storeAttendance
    │
    ├── Validate: emp_id (exists), date (past or today), time_in, time_out
    ├── Convert times to H:i:s
    ├── firstOrNew by emp_id + date (prevents duplicates)
    ├── Set time_in and time_out
    ├── Save record
    └── Return JSON with saved data
```

### 4.5 Attendance Summary (AJAX)

```
GET /attendance/summary?from=YYYY-MM-DD&to=YYYY-MM-DD → AttendanceController@summary
    │
    ├── Join attendance + employees + users
    ├── Filter by date range
    ├── Order by date ASC, then employee name ASC
    └── Return JSON array of records
```

Used by the home page summary modal to display a date-range attendance table before exporting.

---

## 5. Attendance History Page

```
GET /employees/{id}/attendance-page → EmployeeController@attendancePage
    │
    ├── Find Employee with user
    ├── Determine start date:
    │       ├── First attendance date (if any records exist)
    │       └── Fallback: employee created_at date
    ├── End date = today (no future dates)
    ├── Build date range using CarbonPeriod (day by day)
    ├── Load all attendance records for that range, keyed by date
    └── Return EmpAttendance.blade.php with:
            ├── $employee
            ├── $weekDates (all dates in range)
            ├── $attendances (keyed by date string)
            ├── $startDate
            └── $endDate
```

The view loops through every date in the range and shows the attendance record for that day (or a blank row if absent).

---

## 6. Export System

All exports are handled by `ExportController`. Times are converted from 24-hour (DB) to 12-hour (export display).

### 6.1 Export Types

| Export | Route | Format | Scope |
|---|---|---|---|
| Employee attendance | `GET /employees/{id}/attendance/export` | CSV | All records for one employee |
| Employee attendance | `GET /employees/{id}/attendance/export-pdf` | PDF | All records for one employee |
| Today's attendance | `GET /attendance/export/today` | CSV | All employees, today only |
| Today's attendance | `GET /attendance/export/today-pdf` | PDF | All employees, today only |
| Date range summary | `GET /export-summary/csv?from=&to=` | CSV | All employees, date range |
| Date range summary | `GET /export-summary/pdf?from=&to=` | PDF | All employees, date range |

### 6.2 CSV Export Flow

```
ExportController@exportEmployee / exportToday / exportSummaryCsv
    │
    ├── Query attendance records (with employee + user joins)
    ├── Create StreamedResponse (streams directly to browser)
    ├── Write CSV headers
    ├── Loop records → convert times → write rows
    └── Set Content-Disposition: attachment → browser downloads file
```

### 6.3 PDF Export Flow

```
ExportController@exportEmployeePdf / exportTodayPdf / exportSummaryPdf
    │
    ├── Query attendance records
    ├── Load Blade view from resources/views/PDF/
    │       ├── employee_pdf.blade.php → per-employee layout
    │       ├── today_pdf.blade.php → today's records (landscape)
    │       └── summary_pdf.blade.php → date range summary (landscape)
    ├── Pdf::loadView() → render Blade to PDF via DomPDF
    └── Return pdf->download($filename)
```

---

## 7. Social Login Flow

See `documentation/socialite.md` for the full detailed flow.

**Summary:**

```
User clicks Google/Facebook login
    │
    ├── GET /auth/google → SocialAuthController@redirectToGoogle
    │       └── Socialite::driver('google')->stateless()->redirect()
    │
    ├── [User logs in on Google/Facebook]
    │
    └── GET /auth/google/callback → SocialAuthController@handleGoogleCallback
            ├── Fetch user info from provider
            ├── findOrCreateSocialUser():
            │       ├── If email exists → update provider_id + token
            │       └── If not → create new User (role = employee)
            ├── Auth::login($user)
            └── Redirect to home
```

---

## 8. Database Structure

### Tables

| Table | Purpose |
|---|---|
| `users` | Auth accounts (name, email, password, role, provider info) |
| `employees` | Employee profiles (user_id FK, position, emp_pic) |
| `attendance` | Daily attendance records (emp_id FK, date, time_in, time_out) |

### Relationships

```
User (1) ──── (1) Employee ──── (many) Attendance
```

- `User → Employee`: One-to-one (`user_id` on employees)
- `Employee → Attendance`: One-to-many (`emp_id` on attendance)
- `Attendance → Employee`: Belongs to

### Key Columns

**users**
- `role` — `admin` or `employee`
- `provider` — `google`, `facebook`, or null
- `provider_id`, `provider_token` — OAuth data

**employees**
- `user_id` — FK to users
- `position` — job title
- `emp_pic` — relative path in storage/app/public/employees/

**attendance**
- `emp_id` — FK to employees
- `date` — `YYYY-MM-DD`
- `time_in`, `time_out` — `H:i:s` (24-hour format)

---

## 9. Route Reference

| Method | URI | Controller | Action |
|---|---|---|---|
| GET | `/` | EmployeeController | index — home page |
| POST | `/admin/login` | AuthController | login |
| POST | `/admin/logout` | AuthController | logout |
| POST | `/employees` | EmployeeController | store — add employee |
| PUT | `/employees/update` | EmployeeController | update — edit employee |
| DELETE | `/employees/{id}` | EmployeeController | destroy — delete employee |
| GET | `/employees/{id}/attendance-page` | EmployeeController | attendancePage |
| GET | `/employees/search` | EmployeeController | search (AJAX) |
| POST | `/attendance/time-in` | AttendanceController | timeIn |
| POST | `/attendance/time-out` | AttendanceController | timeOut |
| PUT | `/attendance/{id}` | AttendanceController | updateAttendance |
| POST | `/attendance` | AttendanceController | storeAttendance |
| GET | `/attendance/summary` | AttendanceController | summary (AJAX) |
| GET | `/employees/{id}/attendance/export` | ExportController | exportEmployee (CSV) |
| GET | `/employees/{id}/attendance/export-pdf` | ExportController | exportEmployeePdf |
| GET | `/attendance/export/today` | ExportController | exportToday (CSV) |
| GET | `/attendance/export/today-pdf` | ExportController | exportTodayPdf |
| GET | `/export-summary/csv` | ExportController | exportSummaryCsv |
| GET | `/export-summary/pdf` | ExportController | exportSummaryPdf |
| GET | `/auth/google` | SocialAuthController | redirectToGoogle |
| GET | `/auth/google/callback` | SocialAuthController | handleGoogleCallback |
| GET | `/auth/facebook` | SocialAuthController | redirectToFacebook |
| GET | `/auth/facebook/callback` | SocialAuthController | handleFacebookCallback |
