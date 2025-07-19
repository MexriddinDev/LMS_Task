<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Student;
use App\Models\Debt;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // Log fasadini import qilish

class PaymentService
{
    public function processPayment(array $paymentData): Payment
    {
        return DB::transaction(function () use ($paymentData) {
            $payment = Payment::create($paymentData);
            Log::info("PaymentService: Yangi to'lov yaratildi - ID: {$payment->id}, Tur: {$payment->type}, Miqdor: {$payment->amount}, Talaba: {$payment->student_id}.");

            if ($payment->type === 'debt') {
                $this->processDebtPayment($payment, $paymentData['debt_id'] ?? null);
            } else {
                $this->processBalancePayment($payment);
            }

            return $payment;
        });
    }

    private function processDebtPayment(Payment $payment, ?int $specificDebtId = null): void
    {
        $student = Student::find($payment->student_id);
        if (!$student) {
            Log::warning("PaymentService: processDebtPayment - Talaba topilmadi (ID: {$payment->student_id}).");
            return;
        }

        $remainingAmount = $payment->amount;
        Log::info("PaymentService: processDebtPayment - Talaba {$student->id} uchun {$remainingAmount} miqdorida qarz to'lovi boshlandi. Talaba balansi: {$student->balance}.");


        if ($specificDebtId) {
            $debt = Debt::find($specificDebtId);
            if ($debt && $debt->student_id === $payment->student_id && !$debt->is_paid) {
                Log::info("PaymentService: Aniq qarz ({$specificDebtId}) to'lanmoqda. Qolgan summa: {$remainingAmount}.");
                $remainingAmount = $this->payDebt($debt, $remainingAmount);
            } else {
                Log::info("PaymentService: Aniq qarz ({$specificDebtId}) topilmadi, talabaga tegishli emas yoki allaqachon to'langan. Qolgan summa: {$remainingAmount}.");
            }
        }

        if ($remainingAmount > 0) {
            Log::info("PaymentService: Qolgan summa ({$remainingAmount}) bilan to'lanmagan qarzlarni yopishga urinilmoqda (umumiy qarzlar ro'yxatidan).");
            $unpaidDebts = Debt::where('student_id', $payment->student_id)
                ->where('is_paid', false)
                ->when($specificDebtId, function ($query) use ($specificDebtId) {
                    return $query->where('id', '!=', $specificDebtId);
                })
                ->orderBy('created_at', 'asc')
                ->get();

            if ($unpaidDebts->isEmpty()) {
                Log::info("PaymentService: Talaba {$student->id} uchun to'lanmagan qarzlar topilmadi. Qolgan summa {$remainingAmount} balansga qo'shiladi.");
            }

            foreach ($unpaidDebts as $debt) {
                if ($remainingAmount <= 0) {
                    Log::info("PaymentService: Qolgan summa tugadi. Qarzlarni yopish to'xtatildi.");
                    break;
                }
                Log::info("PaymentService: Qarz ID: {$debt->id} to'lanmoqda (umumiy ro'yxatdan). Summa: {$remainingAmount}.");
                $remainingAmount = $this->payDebt($debt, $remainingAmount);
            }
        }

        // Agar qarzlar to'langandan keyin ham summa qolsa, talabaning balansiga qo'shish
        if ($remainingAmount > 0) {
            $student->balance += $remainingAmount;
            Log::info("PaymentService: Qarzlarni yopgandan so'ng qolgan summa ({$remainingAmount}) talaba ({$student->id}) balansiga qo'shilmoqda. Yangi balans: {$student->balance}.");
        } else {
            Log::info("PaymentService: Qarz to'lovidan keyin qolgan summa yo'q. Talaba balansi: {$student->balance}.");
        }

        $student->save();
        Log::info("PaymentService: Talaba ({$student->id}) balansi yakuniy saqlandi: {$student->balance}.");
    }

    private function processBalancePayment(Payment $payment): void
    {
        $student = Student::find($payment->student_id);
        if (!$student) {
            Log::warning("PaymentService: processBalancePayment - Talaba topilmadi (ID: {$payment->student_id}).");
            return;
        }
        Log::info("PaymentService: Balansga to'lov ({$payment->amount}) talaba ({$student->id}) balansiga qo'shilmoqda. Eskisi: {$student->balance}.");
        $student->balance += $payment->amount;
        $student->save();

        Log::info("PaymentService: Talaba ({$student->id}) balansi yangilandi: {$student->balance}. Qarzlarni yopishga urinilmoqda (balans to'lovi hisobiga).");
        $this->redeemDebtsFromBalance($student);
    }

    private function payDebt(Debt $debt, float $amount): float
    {
        $debtRemaining = $debt->amount - $debt->paid_amount;
        $paymentAmount = min($amount, $debtRemaining);

        $debt->paid_amount += $paymentAmount;
        if ($debt->paid_amount >= $debt->amount) {
            $debt->is_paid = true;
            Log::info("PaymentService: Qarz ID: {$debt->id} to'liq to'landi. Miqdor: {$paymentAmount}.");
        } else {
            Log::info("PaymentService: Qarz ID: {$debt->id} qisman to'landi. Miqdor: {$paymentAmount}. Qolgan: " . ($debt->amount - $debt->paid_amount));
        }
        $debt->save();

        return $amount - $paymentAmount;
    }

    public function redeemDebtsFromBalance(Student $student): void
    {
        Log::info("PaymentService: redeemDebtsFromBalance - Talaba ({$student->id}) uchun balansdan qarzlarni yopishga urinilmoqda. Joriy balans: {$student->balance}.");

        if ($student->balance <= 0) {
            Log::info("PaymentService: redeemDebtsFromBalance - Talaba ({$student->id}) balansi nol yoki manfiy ({$student->balance}). Qarzlarni yopishga urinilmaydi.");
            return;
        }


        $unpaidDebts = Debt::where('student_id', $student->id)
            ->where('is_paid', false)
            ->orderBy('created_at', 'asc')
            ->get();

        if ($unpaidDebts->isEmpty()) {
            Log::info("PaymentService: redeemDebtsFromBalance - Talaba ({$student->id}) uchun to'lanmagan qarzlar topilmadi.");
        }

        foreach ($unpaidDebts as $debt) {
            if ($student->balance <= 0) {
                Log::info("PaymentService: redeemDebtsFromBalance - Talaba balansi tugadi ({$student->balance}). Qarzlarni yopish to'xtatildi.");
                break;
            }

            $debtRemaining = $debt->amount - $debt->paid_amount;
            if ($debtRemaining <= 0) {
                // Agar qarz allaqachon to'langan bo'lsa (lekin is_paid FALSE turgan bo'lsa ham)
                $debt->is_paid = true;
                $debt->save();
                Log::info("PaymentService: redeemDebtsFromBalance - Qarz ID: {$debt->id} allaqachon to'langan edi, is_paid TRUE ga o'rnatildi.");
                continue;
            }

            $paymentAmount = min($student->balance, $debtRemaining);

            $debt->paid_amount += $paymentAmount;
            if ($debt->paid_amount >= $debt->amount) {
                $debt->is_paid = true;
                Log::info("PaymentService: redeemDebtsFromBalance - Qarz ID: {$debt->id} (balansdan) to'liq to'landi. Miqdor: {$paymentAmount}.");
            } else {
                Log::info("PaymentService: redeemDebtsFromBalance - Qarz ID: {$debt->id} (balansdan) qisman to'landi. Miqdor: {$paymentAmount}. Qolgan: " . ($debt->amount - $debt->paid_amount));
            }
            $debt->save();

            $student->balance -= $paymentAmount;
            Log::info("PaymentService: redeemDebtsFromBalance - Talaba ({$student->id}) balansi kamaytirildi. Yangi balans: {$student->balance}.");
        }

        $student->save();
        Log::info("PaymentService: redeemDebtsFromBalance - Talaba ({$student->id}) balansi yakuniy saqlandi: {$student->balance}.");
    }

    public function generateMonthlyDebt(int $groupId, float $amount, string $monthString): void
    {
        Log::info("PaymentService: generateMonthlyDebt - Guruh ID: {$groupId} uchun {$monthString} oyi qarzi yaratishga urinilmoqda. Miqdor: {$amount}.");

        $studentIds = DB::table('group_students')
            ->where('group_id', $groupId)
            ->pluck('student_id');

        if ($studentIds->isEmpty()) {
            Log::info("PaymentService: generateMonthlyDebt - Guruh ID: {$groupId} da talabalar topilmadi.");
            return;
        }

        foreach ($studentIds as $studentId) {
            Log::info("PaymentService: generateMonthlyDebt - Talaba {$studentId} uchun qarz yaratish jarayoni boshlandi.");

            $existingDebt = Debt::where('student_id', $studentId)
                ->where('group_id', $groupId)
                ->where('month', $monthString)
                ->first();

            if ($existingDebt) {
                Log::info("PaymentService: generateMonthlyDebt - Talaba {$studentId} uchun guruh {$groupId} da {$monthString} oyi qarzi allaqachon mavjud (ID: {$existingDebt->id}). O'tkazib yuborilmoqda.");
                // Agar qarz allaqachon bor bo'lsa, uni qayta yaratmaslik uchun continue.
                // Lekin uning holatini (to'langan/to'lanmagan) tekshirish va balansdan yopish logikasini bu yerga qo'shish kerak.
                $student = Student::find($studentId);
                if ($student) {
                    // Agar talabaning balansi yuqori bo'lib, bu qarz to'lanmagan bo'lsa, uni yopishga urinish
                    if (!$existingDebt->is_paid && $student->balance > 0) {
                        Log::info("PaymentService: Mavjud qarz ({$existingDebt->id}) to'lanmagan va talaba balansida pul bor. Balans hisobiga yopishga urinilmoqda.");
                        $this->redeemDebtsFromBalance($student);
                    }
                }
                continue;
            }

            // Yangi qarzni yaratamiz
            try {
                Debt::create([
                    'student_id' => $studentId,
                    'group_id' => $groupId,
                    'amount' => $amount,
                    'paid_amount' => 0,
                    'is_paid' => false,
                    'month' => $monthString,
                    'description' => "Monthly fee for {$monthString}",
                ]);
                Log::info("PaymentService: generateMonthlyDebt - Talaba {$studentId} uchun guruh {$groupId} da {$monthString} oyi uchun yangi qarz yaratildi. Miqdor: {$amount}.");

                // Qarz yaratilgandan so'ng, talabaning balansida pul bo'lsa, avtomatik ravishda qarzni yopishga harakat qilamiz
                $student = Student::find($studentId);
                if ($student) {
                    $this->redeemDebtsFromBalance($student);
                } else {
                    Log::warning("PaymentService: generateMonthlyDebt - Talaba topilmadi (ID: {$studentId}) qarz yaratilgandan keyin.");
                }
            } catch (\Exception $e) {
                // Unique Constraint Violation (agar avvalroq yaratilgan bo'lsa)
                Log::error("PaymentService: Qarz yaratishda xato yuz berdi. Ehtimol duplikat. Xato: {$e->getMessage()}. Talaba: {$studentId}, Guruh: {$groupId}, Oy: {$monthString}.");
            }
        }
        Log::info("PaymentService: generateMonthlyDebt - Guruh ID: {$groupId} uchun qarz yaratish jarayoni yakunlandi.");
    }
}
