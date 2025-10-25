php artisan make:migration update_users_table_for_roles --table=users
php artisan make:migration create_employees_table --create=tbl_employees
php artisan make:migration create_attendance_table --create=tbl_attendance

npm install -D tailwindcss@3 postcss autoprefixer
npx tailwindcss init -p
php artisan make:controller AuthController
php artisan make:controller EmployeeController
php artisan make:migration update_employees_table_remove_email_fullname
php artisan make:controller AttendanceController
composer require laravel/socialite
composer require barryvdh/laravel-dompdf
