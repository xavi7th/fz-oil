<?php

namespace App\Modules\SuperAdmin\Listeners;

use Illuminate\Events\Dispatcher;

class CacheEventSubscriber
{
  /**
   * Register the listeners for the subscriber.
   */
  public function subscribe(Dispatcher $events)
  {
    // $events->listen(ProductAccessoryStockUpdated::class, 'App\Modules\SuperAdmin\Listeners\CacheEventSubscriber@onProductAccessoryStockUpdated');
  }

  // static function onProductAccessoryStockUpdated(ProductAccessoryStockUpdated $event)
  // {
  //   Cache::forget('allProductAccessories');
  //   Cache::forget('inStockProductAccessories');
  // }

}
