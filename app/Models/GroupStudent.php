<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupStudent extends Model
{
    protected $fillable = [
        'group_id',
        'student_id',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

}
