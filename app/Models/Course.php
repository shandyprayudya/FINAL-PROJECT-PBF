<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'image'
    ];

    public function courseCategory()
    {
        return $this->belongsTo(CourseCategory::class); 
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function material()
    {
        return $this->hasMany(Material::class);
    }

    public function enrollment()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function lesson()
    {
        return $this->hasMany(Lesson::class);
    }
}
