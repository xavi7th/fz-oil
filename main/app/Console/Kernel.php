<?php

namespace App\Console;

use RachidLaasri\Travel\Travel;
use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Scheduling\Schedule;
use App\Modules\PurchaseOrder\Models\PurchaseOrder;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Modules\SuperAdmin\Http\Controllers\SuperAdminController;

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

    $schedule->command('backup:run --only-db')->everyMinute();

    $schedule->call(function () {
      $statistics = collect(SuperAdminController::getDashboardStatistics())->merge(['orders' => PurchaseOrder::today()->with('product_type', 'buyer', 'bank')->get()])->toArray();

      $data["email"] = "xav@y.com";
      $data["title"] = "From ItSolutionStuff.com";
      $data["body"] = "This is Demo";

      Mail::send('purchaseorder::report',  compact('statistics'), function($message)use($data) {
        $message->to($data["email"], $data["email"])
                ->subject($data["title"]);
      });
    })->everyMinute();

    $schedule->command('telescope:prune')->daily();

    $schedule->command('backup:clean')->weekly()->at('12:00');
    $schedule->command('backup:run')->weekly()->at('16:00');

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
