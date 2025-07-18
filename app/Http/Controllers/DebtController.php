<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use Illuminate\Http\Request;

class DebtController extends Controller
{
    public function index(){

        $debts = Debt::with(['student', 'group'])->get();
        return inertia('Debts/Debts', ['debts' => $debts]);
    }
}
