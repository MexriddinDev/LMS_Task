<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'teacher_id',
        'monthly_fee',
        'start_date',
        'date',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'group_students', 'group_id', 'student_id');
    }

    public function debts()
    {
        return $this->hasMany(Debt::class);
    }
}
