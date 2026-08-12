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
        // Check and send task reminders every 5 minutes
        $schedule->command('tasks:send-reminders')->everyFiveMinutes();

        // Clean up old soft-deleted tasks monthly
        $schedule->command('model:prune', ['--model' => \App\Models\Task::class])->monthly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
