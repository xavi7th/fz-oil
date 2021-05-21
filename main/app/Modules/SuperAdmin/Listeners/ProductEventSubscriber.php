<?php

namespace App\Modules\SuperAdmin\Listeners;

use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use App\Modules\SuperAdmin\Events\ProductSaved;
use App\Modules\SuperAdmin\Events\ProductUpdated;
use App\Modules\SuperAdmin\Notifications\ErrorAlertNotification;

class ProductEventSubscriber implements ShouldQueue
{

  private $request;

  public function __construct(Request $request)
  {
    $this->request = $request;
  }

  /**
   * Register the listeners for the subscriber.
   *
   * @param  \Illuminate\Events\Dispatcher  $events
   */
  public function subscribe($events)
  {
    $events->listen(ProductUpdated::class, 'App\Modules\SuperAdmin\Listeners\ProductEventSubscriber@onProductUpdated');
    $events->listen(ProductSaved::class, 'App\Modules\SuperAdmin\Listeners\ProductEventSubscriber@onProductSaved');
  }


  static function onProductUpdated(ProductUpdated $event)
  {
    if (is_null(request()->user()) || ($event->product->isDirty('is_paid') && $event->product->is_local)) {
    } else {
      request()->user()->product_histories()->create([
        'product_id' => $event->product->id,
        'product_type' => get_class($event->product),
        'product_status_id' => $event->product->product_status_id,
      ]);
    }
  }


  static function onProductSaved(ProductSaved $event)
  {

  }

  /**
   * Handle a job failure.
   *
   * @param  \Throwable  $exception
   * @return void
   */
  public function failed($event, $th)
  {
    Notification::route('slack', config('logging.channels.slack.url'))->notify(new ErrorAlertNotification($th,'ProductEventSubscriber queue work failed'));
  }
}
