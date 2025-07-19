<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\GenerateMonthlyDebts::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // MUHIM: Faqatgina bitta qator faol bo'lsin!
        // Har 1 daqiqada ishga tushirish (TEST UCHUN)
        $schedule->command('debts:generate-monthly')->everyMinute();

        // Agar production ga qo'yayotgan bo'lsangiz, quyidagi qatorni faollashtiring
        // va yuqoridagini o'chiring, va cron jobni to'g'ri sozlang.
        // $schedule->command('debts:generate-monthly')->monthlyOn(1, '01:00'); // Har oyning 1-sanasida soat 01:00 da
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
