# Model Relationships

## Relationships

- **User (1) ---- (1) Employee ---- (many) Attendance**
- **User ↔ Employee**: 1:1
- **Employee ↔ Attendance**: 1:N

## Usage

- `$user->employee` → gets the employee profile.
- `$employee->user` → gets the user account.
- `$employee->attendances` → gets all attendance records.
- `$attendance->employee` → gets the employee linked to that attendance.
