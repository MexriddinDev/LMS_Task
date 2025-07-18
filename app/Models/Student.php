<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'phone',
        'birth_date',
        'balance',
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_students', 'student_id', 'group_id');
    }

    public function groupStudents()
    {
        return $this->hasMany(GroupStudent::class, 'student_id');
    }

    public function debts()
    {
        return $this->hasMany(Debt::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

}
