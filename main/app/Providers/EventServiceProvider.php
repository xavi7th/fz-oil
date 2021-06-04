<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Events\MigrationsEnded;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use App\Modules\SuperAdmin\Listeners\EnsureDatabaseIsSetupForSeeding;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
  /**
   * The event listener mappings for the application.
   *
   * @var array
   */
  protected $listen = [
    Registered::class => [
      SendEmailVerificationNotification::class,
    ],
    MigrationsEnded::class => [
      EnsureDatabaseIsSetupForSeeding::class
    ]
  ];

  /**
   * Register any events for your application.
   *
   * @return void
   */
  public function boot()
  {
    parent::boot();

    //
  }
}
