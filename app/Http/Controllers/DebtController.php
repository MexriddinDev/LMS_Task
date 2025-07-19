<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use Illuminate\Http\Request;

class DebtController extends Controller
{
    public function index()
    {
        // Eager load the 'student' and 'group' relationships directly on the Debt model
        $debts = Debt::with(['student', 'group'])->get();

        // Calculate paid and unpaid debts
        $paidDebtsCount = $debts->where('is_paid', true)->count();
        $unpaidDebtsCount = $debts->where('is_paid', false)->count();

        return inertia('Debts/Debts', [
            'debts' => $debts,
            'paidDebtsCount' => $paidDebtsCount,
            'unpaidDebtsCount' => $unpaidDebtsCount,
        ]);
    }
}
