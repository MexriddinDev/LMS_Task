<?php
namespace App\Observers;
use App\Models\Student;
use App\Services\PaymentService;
class StudentObserver
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function created(Student $student): void
    {
        if ($student->group_id) {
            $group = $student->group; // ← relation kerak!
            $this->paymentService->processPayment([
                'student_id' => $student->id,
                'amount' => $group->price,
                'reason' => 'Guruh uchun avtomatik to‘lov',
            ]);
        }
    }
}
