<?php

namespace App\Modules\SuperAdmin\Listeners;

use Illuminate\Database\Events\MigrationsEnded;
use App\Modules\SuperAdmin\Database\State\EnsureStaffRolesArePresentInDatabase;
use App\Modules\SuperAdmin\Database\State\EnsureProductTypesArePresentInDatabase;

class EnsureDatabaseIsSetupForSeeding
{
  /**
   * Handle the event.
   *
   * @param  \Illuminate\Database\Events\MigrationsEnded  $event
   * @return void
   */
  public function handle(MigrationsEnded $event)
  {
    collect([
      new EnsureProductTypesArePresentInDatabase,
      new EnsureStaffRolesArePresentInDatabase,
    ])->each->__invoke();
  }
}
