# EAMS — Models & Database Relationships

---

## Relationships Overview

```
User (1) ──── (1) Employee ──── (many) Attendance
```

| Relationship | Type | Description |
|---|---|---|
| User → Employee | One-to-One | Each user account has one employee profile |
| Employee → User | Belongs To | Each employee belongs to one user account |
| Employee → Attendance | One-to-Many | Each employee has many attendance records |
| Attendance → Employee | Belongs To | Each attendance record belongs to one employee |

---

## User Model

**Table:** `users`

| Column | Type | Notes |
|---|---|---|
| `id` | bigint | Primary key |
| `name` | string | Full name |
| `email` | string | Unique |
| `password` | string | Hashed |
| `role` | string | `admin` or `employee` |
| `provider` | string | `google`, `facebook`, or null |
| `provider_id` | string | OAuth provider user ID |
| `provider_token` | text | OAuth access token |

**Relationships defined in model:**
- `employee()` → `hasOne(Employee::class)` *(implied by Employee's belongsTo)*

---

## Employee Model

**Table:** `employees`

| Column | Type | Notes |
|---|---|---|
| `id` | bigint | Primary key |
| `user_id` | bigint | FK → users.id |
| `position` | string | Job title |
| `emp_pic` | string | Relative path in storage/app/public/employees/ |

**Relationships:**
```php
// Each employee belongs to one user account
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}

// Each employee has many attendance records, ordered latest first
public function attendances(): HasMany
{
    return $this->hasMany(Attendance::class, 'emp_id')
                ->orderBy('date', 'desc');
}
```

**Usage:**
```php
$employee->user          // → User model
$employee->user->name    // → employee's full name
$employee->user->email   // → employee's email
$employee->attendances   // → collection of Attendance records
```

---

## Attendance Model

**Table:** `attendance`

| Column | Type | Notes |
|---|---|---|
| `id` | bigint | Primary key |
| `emp_id` | bigint | FK → employees.id |
| `date` | date | `YYYY-MM-DD` |
| `time_in` | time | `H:i:s` (24-hour format) |
| `time_out` | time | `H:i:s` (24-hour format), nullable |

**Relationships:**
```php
// Each attendance record belongs to one employee
public function employee(): BelongsTo
{
    return $this->belongsTo(Employee::class, 'emp_id');
}
```

**Usage:**
```php
$attendance->employee           // → Employee model
$attendance->employee->user     // → User model (chained)
```

**Time format note:** Times are stored in 24-hour format (`H:i:s`) in the database. They are converted to 12-hour format (`h:i A`) in views and exports using Carbon:
```php
Carbon::parse($attendance->time_in)->format('h:i A')  // → "09:30 AM"
```

---

## Common Query Patterns

```php
// Get employee with user and all attendances
$employee = Employee::with(['user', 'attendances'])->findOrFail($id);

// Get today's attendance for a specific employee
$today = now()->toDateString();
$todayAttendance = Attendance::where('emp_id', $employee->id)
    ->where('date', $today)
    ->first();

// Get or create today's attendance record (used in time-in/out)
$attendance = Attendance::firstOrNew([
    'emp_id' => $employee->id,
    'date'   => $today,
]);

// Get all employees with today's status
$employees = Employee::with(['user', 'attendances'])->paginate(5);
$employees->getCollection()->transform(function ($employee) use ($today) {
    $todayAttendance = $employee->attendances->firstWhere('date', $today);
    $employee->timeInDone  = $todayAttendance && $todayAttendance->time_in;
    $employee->timeOutDone = $todayAttendance && $todayAttendance->time_out;
    return $employee;
});
```

---

## Migrations Order

1. `create_users_table` — base users table
2. `create_cache_table` — Laravel cache
3. `create_jobs_table` — Laravel queue jobs
4. `update_users_table_add_role` — adds `role` column to users
5. `create_employees_table` — employees with user_id FK
6. `create_attendance_table` — attendance with emp_id FK
7. `add_password_and_provider_to_employees_table` — OAuth columns (later dropped)
8. `drop_password_from_employees_table` — cleanup migration
