<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'position',
        'emp_pic',
        'password',
        'login_provider',
    ];

    // Each employee belongs to one user)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Each employee can have multiple attendance records, then sorted by date descending (latest first)
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'emp_id')
                    ->orderBy('date', 'desc');
    }

}
