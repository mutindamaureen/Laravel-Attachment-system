<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'date',
        'description',
        'hours',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function lecturerComments()
    {
        return $this->hasMany(LecturerComment::class);
    }

    public function supervisorComments()
    {
        return $this->hasMany(SupervisorComment::class);
    }

}
