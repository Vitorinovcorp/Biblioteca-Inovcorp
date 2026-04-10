<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\CreateAdmin::class,
        \App\Console\Commands\SendReminderEmails::class,
        \App\Console\Commands\PrecomputeRecommendations::class,
        \App\Console\Commands\CheckAbandonedCarts::class, 
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('reminders:send')->dailyAt('08:00');
        $schedule->command('carts:check-abandoned')->hourly(); 
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}