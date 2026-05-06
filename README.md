# EAMS — Employee Attendance Management System

A web-based attendance tracking system built with **Laravel**, **Tailwind CSS**, and **Laravel Socialite**. Designed for small to medium organizations to manage employee records and daily attendance with admin-controlled access.

---

## What It Does

- Admins manage employee records (add, edit, delete, view)
- Employees time in and time out using their credentials via a password modal
- Admins can manually add or edit attendance records for missed days
- Attendance data is exportable as **CSV** or **PDF** (per employee, today's records, or date-range summaries)
- Employees can also log in via **Google** or **Facebook** (Laravel Socialite)

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 11 |
| Frontend | Blade Templates, Tailwind CSS |
| Database | SQLite (local) / MySQL (production) |
| Auth | Laravel Auth + Laravel Socialite (Google, Facebook) |
| PDF Export | barryvdh/laravel-dompdf |
| Date Handling | Carbon |

---

## Roles

| Role | Access |
|---|---|
| `admin` | Full access — manage employees, view/edit all attendance, export reports |
| `employee` | Time in / time out only via password modal on the home page |

---

## Getting Started

### Requirements
- PHP 8.2+
- Composer
- Node.js + npm

### Installation

```bash
git clone <repo-url>
cd EAMS

composer install
npm install

cp .env.example .env
php artisan key:generate

# Configure your database in .env, then:
php artisan migrate

php artisan storage:link
npm run dev
php artisan serve
```

### Default Admin Account
Seed or manually create a user with `role = admin` in the `users` table.

---

## Environment Variables

```env
APP_URL=http://localhost

DB_CONNECTION=sqlite
# or mysql with DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=
FACEBOOK_REDIRECT_URI=http://localhost:8000/auth/facebook/callback
```

---

## Project Structure

```
app/
├── Http/Controllers/
│   ├── AuthController.php          # Admin login/logout
│   ├── EmployeeController.php      # Employee CRUD + attendance page + search
│   ├── AttendanceController.php    # Time in, time out, update, store, summary
│   ├── ExportController.php        # CSV and PDF exports
│   └── SocialAuthController.php    # Google and Facebook OAuth
├── Models/
│   ├── User.php
│   ├── Employee.php
│   └── Attendance.php
resources/views/
├── home.blade.php                  # Main dashboard (employee table + modals)
├── EmpAttendance.blade.php         # Per-employee attendance history
├── layouts/app.blade.php           # Base layout
├── partials/                       # Modals (add, edit, time in/out, admin login)
└── PDF/                            # PDF export templates
documentation/
├── how-it-works.md                 # Full system flow documentation
├── future-works.md                 # Planned improvements and known bugs
├── models.md                       # Model relationships
├── routes.md                       # All routes reference
├── socialite.md                    # Social login flow
└── Notes.md                        # Dev notes and artisan commands
```

---

## Documentation

| File | Description |
|---|---|
| `documentation/how-it-works.md` | Full system flow — auth, attendance, exports |
| `documentation/future-works.md` | Roadmap, improvements, known bugs |
| `documentation/models.md` | Model relationships and DB structure |
| `documentation/routes.md` | All routes with methods and controllers |
| `documentation/socialite.md` | Google and Facebook OAuth flow |

---

## License

For educational and portfolio use. All rights reserved.
