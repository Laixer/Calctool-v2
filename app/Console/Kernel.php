<?php

namespace Calctool\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \Calctool\Console\Commands\Inspire::class,
        \Calctool\Console\Commands\DropHard::class,
        \Calctool\Console\Commands\MaterialImport::class,
        \Calctool\Console\Commands\StorageClear::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')->hourly();
    }
}
