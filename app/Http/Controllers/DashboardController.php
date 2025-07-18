<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(){
        $studentsCount = Student::count();
        $groupsCount = Group::count();
        $teachersCount= Teacher::count();
        $paymentsCount = (Payment::sum('amount')) ?? 0;
        return Inertia::render('Dashboard', [
            'studentsCount' => $studentsCount,
            'groupsCount' => $groupsCount,
            'teachersCount' => $teachersCount,
            'paymentsCount' => $paymentsCount,
        ]);

    }
}
