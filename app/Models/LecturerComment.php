<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LecturerComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'lecturer_id',
        'student_id',
        'comment',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }
}
