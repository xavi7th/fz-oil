<?php

namespace App\Modules\SuperAdmin\Listeners;

use Illuminate\Events\Dispatcher;
use App\Modules\SuperAdmin\Events\ProductSaleRecordConfirmed;

class ProductSaleRecordEventSubscriber //implements ShouldQueue
{
  /**
   * Create the event listener.
   *
   * @return void
   */
  public function __construct()
  {
    //
  }

  /**
   * Register the listeners for the subscriber.
   */
  public function subscribe(Dispatcher $events)
  {
    $events->listen(ProductSaleRecordConfirmed::class, 'App\Modules\SuperAdmin\Listeners\ProductSaleRecordEventSubscriber@onProductSaleRecordConfirmed');
  }

  /**
   * Handle a job failure.
   *
   * @param  \Throwable  $exception
   * @return void
   */
  public function failed($event, $th)
  {
    // Notification::route('slack', config('logging.channels.slack.url'))->notify(new ErrorAlertNotification($th,'ProductSaleRecordEventSubscriber queue work failed'));
  }
}
