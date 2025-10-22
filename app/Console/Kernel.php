<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Check low stock every hour
        $schedule->command('inventory:check-low-stock')->hourly();
        
        // Expire loyalty points daily at midnight
        $schedule->command('loyalty:expire-points')->daily();
        
        // Database backup daily at 2 AM
        $schedule->command('backup:run')->dailyAt('02:00');
        
        // Clean old notifications weekly
        $schedule->command('notifications:clean')->weekly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
