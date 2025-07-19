<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Student;
use App\Models\Debt;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    /**
     * Process a payment and handle automatic debt redemption
     *
     * @param array $paymentData
     * @return Payment
     */
    public function processPayment(array $paymentData): Payment
    {
        return DB::transaction(function () use ($paymentData) {
            // Create the payment record
            $payment = Payment::create($paymentData);

            if ($payment->type === 'debt') {
                $this->processDebtPayment($payment, $paymentData['debt_id'] ?? null);
            } else {
                $this->processBalancePayment($payment);
            }

            return $payment;
        });
    }

    /**
     * Process a debt payment
     *
     * @param Payment $payment
     * @param int|null $specificDebtId
     */
    private function processDebtPayment(Payment $payment, ?int $specificDebtId = null): void
    {
        $student = Student::find($payment->student_id);
        $remainingAmount = $payment->amount;

        if ($specificDebtId) {
            // Pay specific debt first
            $debt = Debt::find($specificDebtId);
            if ($debt && $debt->student_id === $payment->student_id && !$debt->is_paid) {
                $remainingAmount = $this->payDebt($debt, $remainingAmount);
            }
        }

        // If there's remaining amount, pay other debts from oldest to newest
        if ($remainingAmount > 0) {
            $unpaidDebts = Debt::where('student_id', $payment->student_id)
                ->where('is_paid', false)
                ->when($specificDebtId, function ($query) use ($specificDebtId) {
                    return $query->where('id', '!=', $specificDebtId);
                })
                ->orderBy('created_at', 'asc')
                ->get();

            foreach ($unpaidDebts as $debt) {
                if ($remainingAmount <= 0) break;
                $remainingAmount = $this->payDebt($debt, $remainingAmount);
            }
        }

        // If there's still remaining amount, add it to student's balance
        if ($remainingAmount > 0) {
            $student->balance += $remainingAmount;
            $student->save();
        }
    }

    /**
     * Process a balance payment
     *
     * @param Payment $payment
     */
    private function processBalancePayment(Payment $payment): void
    {
        $student = Student::find($payment->student_id);
        $student->balance += $payment->amount;
        $student->save();

        // Automatically redeem debts if balance is positive
        $this->redeemDebtsFromBalance($student);
    }

    /**
     * Pay a specific debt and return remaining amount
     *
     * @param Debt $debt
     * @param float $amount
     * @return float
     */
    private function payDebt(Debt $debt, float $amount): float
    {
        $debtRemaining = $debt->amount - $debt->paid_amount;
        $paymentAmount = min($amount, $debtRemaining);

        $debt->paid_amount += $paymentAmount;
        if ($debt->paid_amount >= $debt->amount) {
            $debt->is_paid = true;
        }
        $debt->save();

        return $amount - $paymentAmount;
    }

    /**
     * Automatically redeem debts from student balance
     *
     * @param Student $student
     */
    public function redeemDebtsFromBalance(Student $student): void
    {
        if ($student->balance <= 0) return;

        $unpaidDebts = Debt::where('student_id', $student->id)
            ->where('is_paid', false)
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($unpaidDebts as $debt) {
            if ($student->balance <= 0) break;

            $debtRemaining = $debt->amount - $debt->paid_amount;
            $paymentAmount = min($student->balance, $debtRemaining);

            $debt->paid_amount += $paymentAmount;
            if ($debt->paid_amount >= $debt->amount) {
                $debt->is_paid = true;
            }
            $debt->save();

            $student->balance -= $paymentAmount;
        }

        $student->save();
    }

    /**
     * Generate monthly debt for a group
     *
     * @param int $groupId
     * @param float $amount
     * @param string $month
     */
    public function generateMonthlyDebt(int $groupId, float $amount, string $month): void
    {
        $studentIds = DB::table('group_students')
            ->where('group_id', $groupId)
            ->pluck('student_id');

        foreach ($studentIds as $studentId) {
            Debt::create([
                'student_id' => $studentId,
                'group_id' => $groupId,
                'amount' => $amount,
                'paid_amount' => 0,
                'is_paid' => false,
                'description' => "Monthly fee for {$month}",
            ]);

            // Try to auto-redeem from balance
            $student = Student::find($studentId);
            $this->redeemDebtsFromBalance($student);
        }
    }
}