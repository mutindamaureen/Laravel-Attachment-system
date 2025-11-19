<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySupervisor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'position',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'supervisor_id');
    }
// Add to App\Models\CompanySupervisor

    public function comments()
    {
        return $this->hasMany(SupervisorComment::class, 'supervisor_id');
    }

// public function students()
// {
//     return $this->hasMany(Student::class, 'company_supervisor_id');
// }
}
