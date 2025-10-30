<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    /**
     * Users that belong to this role.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }
}
