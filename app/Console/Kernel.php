<?php

namespace App\Console;

use App\Jobs\CheckIfLoggedIn;
use App\Jobs\EndInactivechatSessions;
use App\Jobs\MonthlyPayouts;
use App\Jobs\SendEmailNotifications;
use App\Jobs\UpdateAverageRating;
use App\Jobs\UpdateSales;
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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

	    $schedule->call(function () {
		    EndInactivechatSessions::dispatch();
	    })->everyMinute();

	    $schedule->call(function () {
		    CheckIfLoggedIn::dispatch();
	    })->everyMinute();

	    $schedule->call(function () {
		    SendEmailNotifications::dispatch();
	    })->everyMinute();

	    $schedule->call(function () {
		    UpdateAverageRating::dispatch();
	    })->daily();

	    $schedule->call(function () {
		    UpdateSales::dispatch();
	    })->daily();

//	    if (!env('APP_DEBUG')) {
//		    $schedule->call( function () {
//			    MonthlyPayouts::dispatch();
//		    } )->mondays();
//	    }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
