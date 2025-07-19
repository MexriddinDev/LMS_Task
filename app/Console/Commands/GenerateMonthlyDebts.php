<?php

namespace App\Console\Commands;

use App\Models\Group;
use App\Services\PaymentService;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // Log fasadini import qilish

class GenerateMonthlyDebts extends Command
{
    protected $signature = 'debts:generate-monthly';
    protected $description = 'Guruhlar uchun oyma-oy qarzlarni, ularning boshlanish sanasiga qarab yaratadi.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(PaymentService $paymentService)
    {
        $this->info('Oyma-oy qarz yaratish jarayoni boshlandi...');
        Log::info('GenerateMonthlyDebts buyrug\'i ishga tushdi.');

        $currentMonthString = Carbon::now()->format('Y-m');
        $currentDate = Carbon::now();

        $groups = Group::all();

        if ($groups->isEmpty()) {
            $this->info('Hech qanday guruh topilmadi.');
            Log::info('GenerateMonthlyDebts: Hech qanday guruh topilmadi.');
            return Command::SUCCESS;
        }

        foreach ($groups as $group) {
            $groupStartDate = Carbon::parse($group->start_date);

            Log::info("GenerateMonthlyDebts: Guruh '{$group->name}' (ID: {$group->id}) tekshirilmoqda. Boshlanish sanasi: {$group->start_date}. Joriy sana: {$currentDate->toDateString()}.");

            if ($currentDate->greaterThanOrEqualTo($groupStartDate->startOfDay())) {
                $this->info("Guruh '{$group->name}' ({$group->id}) uchun {$currentMonthString} oyi qarzi yaratishga urinilmoqda...");
                Log::info("GenerateMonthlyDebts: Guruh '{$group->name}' uchun qarz yaratish sharti bajarildi. Joriy oy: {$currentMonthString}.");

                $paymentService->generateMonthlyDebt(
                    $group->id,
                    $group->monthly_fee,
                    $currentMonthString
                );
                Log::info("GenerateMonthlyDebts: Guruh '{$group->name}' uchun generateMonthlyDebt metodi chaqirildi.");

            } else {
                $this->info("Guruh '{$group->name}' ({$group->id}) o'tkazib yuborildi: Boshlanish sanasi ({$group->start_date}) hali kelmagan.");
                Log::info("GenerateMonthlyDebts: Guruh '{$group->name}' o'tkazib yuborildi. Boshlanish sanasi kelmagan.");
            }
        }

        $this->info('Oyma-oy qarz yaratish jarayoni yakunlandi.');
        Log::info('GenerateMonthlyDebts buyrug\'i yakunlandi.');
        return Command::SUCCESS;
    }
}
