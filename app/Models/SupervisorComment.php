<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupervisorComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'supervisor_id',
        'student_id',
        'comment',
        'rating',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(CompanySupervisor::class, 'supervisor_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
}
