<?php

namespace App\Modules\AppUser\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\AppUser\Models\AppUser;
use Illuminate\Database\Eloquent\Model;

class AppUserDatabaseSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Model::unguard();

    app()->environment('local') && factory(AppUser::class, 3)->create()->each(function ($user) {
      // $user->transactions()->saveMany(factory(Transaction::class, 31)->make());
      // $user->transactions()->save(factory(Transaction::class)->make());
    });
  }
}
