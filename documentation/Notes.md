# EAMS — Developer Notes

Quick reference for commands used during development.

---

## Artisan Commands Used

```bash
# Migrations
php artisan make:migration update_users_table_for_roles --table=users
php artisan make:migration create_employees_table --create=employees
php artisan make:migration create_attendance_table --create=attendance

# Controllers
php artisan make:controller AuthController
php artisan make:controller EmployeeController
php artisan make:controller AttendanceController

# Run migrations
php artisan migrate
php artisan migrate:fresh --seed   # reset + reseed
```

---

## Packages Installed

```bash
# Frontend
npm install -D tailwindcss@3 postcss autoprefixer
npx tailwindcss init -p

# Social login
composer require laravel/socialite

# PDF export
composer require barryvdh/laravel-dompdf
```

---

## Storage

```bash
# Expose public storage (run once after setup)
php artisan storage:link
```

Employee profile pictures are stored in `storage/app/public/employees/` and accessed via `/storage/employees/filename.jpg`.

---

## Database Notes

- Default connection: SQLite (`database/database.sqlite`)
- For production: switch to MySQL in `.env`
- Times stored as 24-hour (`H:i:s`), displayed as 12-hour (`h:i A`) in views

---

## Key Decisions

- **Admin-only session login** — employees do not have a web session; they authenticate only via the time-in/time-out password modal
- **`firstOrNew` for attendance** — prevents duplicate records for the same employee + date
- **AJAX search** — returns partial view HTML to avoid full page reloads
- **StreamedResponse for CSV** — streams directly to browser without buffering in memory
