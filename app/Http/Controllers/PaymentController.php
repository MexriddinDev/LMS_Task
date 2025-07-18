<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function index(){
        // Eager load the 'student' relationship to get student details with each payment
        $payments = Payment::with('student')->get();

        // Calculate counts for completed and returned payments
        $completedPaymentsCount = $payments->where('status', 'completed')->count();
        $returnedPaymentsCount = $payments->where('status', 'returned')->count();

        return Inertia::render('Payments/Payments',[
            'payments' => $payments,
            'completedPaymentsCount' => $completedPaymentsCount,
            'returnedPaymentsCount' => $returnedPaymentsCount,
        ]);
    }

    public function show($id){
        $payments = Payment::with('student')->findOrFail($id);

        return Inertia::render('Payments/PaymentShow', [
            'payments' => $payments,
        ]);
    }
}
