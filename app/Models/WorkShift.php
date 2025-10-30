<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkShift extends Model
{
    protected $fillable = [
        'name', 'start_time', 'end_time', 'grace_period', 'is_default', 'is_active'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function employees()
    {
        return $this->hasMany(EmployeeProfile::class);
    }
}
