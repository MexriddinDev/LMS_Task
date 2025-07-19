<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\PaymentService;
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

    public function create(){
        // Get all students with their groups and debts
        $students = \App\Models\Student::with(['groups', 'debts' => function($query) {
            $query->where('is_paid', false)
                  ->orderBy('created_at', 'asc');
        }])->get();

        return Inertia::render('Payments/PaymentCreate', [
            'students' => $students,
        ]);
    }

    public function store(Request $request, PaymentService $paymentService)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'type' => 'required|in:debt,balance',
            'note' => 'nullable|string',
            'debt_id' => 'nullable|exists:debts,id',
            'payment_method' => 'nullable|string|max:50'
        ]);

        // Use the service to process the payment
        $paymentService->processPayment($validated);

        return redirect()->route('payments.index')->with('success', 'To\'lov muvaffaqiyatli yaratildi!');
    }
}
