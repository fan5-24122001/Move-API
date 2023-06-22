<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected $commands = [
        Commands\VimeoProcessCommand::class,
        Commands\DeleteVideosUploading::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('video:process')->everyMinute();
        $schedule->command('video:DeleteVideosUploading')->daily();
        $schedule->command('user:deleteUserNotVerifyEmail')->daily();
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
