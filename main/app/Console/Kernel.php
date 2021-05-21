<?php

namespace App\Console;

use RachidLaasri\Travel\Travel;
use Nwidart\Modules\Facades\Module;
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

  public function bootstrap()
  {
    parent::bootstrap();
    // Travel::to('11months 25 days 12:00am');
  }

  /**
   * Define the application's command schedule.
   *
   * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
   * @return void
   */
  protected function schedule(Schedule $schedule)
  {

    $schedule->command('queue:ensure-processes')->hourly();

    // $schedule->command('database:backup')
    //   ->daily()
    //   // ->emailOutputTo('xavi7th@gmail.com')
    //   ->sendOutputTo(Module::getModulePath('SuperAdmin/Console') . '/1database-backup-log.cson')
    //   ->onFailure(function () {
    //     // ActivityLog::notifyAdmins('Compounding due interests of target savings failed to complete successfully');
    //   });

    // /**
    //  * !See the explanation in ./explanation.cson
    //  */
    // if (app()->environment('local')) {
    //   $schedule->command('queue:work --stop-when-empty --queue=high,low,default')->sendOutputTo(Module::getModulePath('SuperAdmin/Console') . '/queue-jobs.cson');
    // } else {
    //   $schedule->command('queue:restart')->hourly();
    //   $schedule->command('queue:work --sleep=3 --timeout=900 --queue=high,default,low')->runInBackground()->withoutOVerlapping()->everyMinute();
    // }

    $schedule->command('telescope:prune')->daily();

    $schedule->command('backup:clean')->weekly()->at('01:00');
    $schedule->command('backup:run')->weekly()->at('02:00');
    $schedule->command('backup:run  --only-db')->when(fn () => !now()->isSunday())->hourly()->between('7:00', '22:00');

  }



  /**
   * Register the commands for the application.
   *
   * @return void
   */
  protected function commands()
  {
    $this->load(__DIR__ . '/Commands');
    $this->load(Module::getModulePath('SuperAdmin/Console'));

    require base_path('routes/console.php');
  }
}
