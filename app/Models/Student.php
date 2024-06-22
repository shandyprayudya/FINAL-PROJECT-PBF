<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    
    protected $hidden =[
        'email',
        'password'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function enrollment()
    {
        return $this->hasMany(Enrollment::class);
    }
}
