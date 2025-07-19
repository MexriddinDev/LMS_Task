<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'amount',
        'date',
        'note',
        'type',
        'debt_id',
        'payment_method',
    ];

    protected $casts = [
        'date' => 'date', // Cast 'date' to a Carbon instance
        'amount' => 'integer', // Cast amount to integer if it's stored as such
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function debt()
    {
        return $this->belongsTo(Debt::class);
    }
}
