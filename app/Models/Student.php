<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'year_of_study',
        'department_id',
        'course_id',
        'company_id',
        'company_supervisor_id',
        'company_supervisor_comment',
        'lecturer_id',
        'lecturer_comment',
        'daily_activity',
        'report',
        'grade',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function companySupervisor()
    {
        return $this->belongsTo(CompanySupervisor::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
