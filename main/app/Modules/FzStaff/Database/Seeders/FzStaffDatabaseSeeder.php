<?php

namespace App\Modules\FzStaff\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\FzStaff\Models\FzStaff;
use Illuminate\Database\Eloquent\Model;

class FzStaffDatabaseSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    app()->environment('local') && FzStaff::factory()->count(3)->create()->each(function ($user) {
      // $user->transactions()->saveMany(factory(Transaction::class, 31)->make());
      // $user->transactions()->save(factory(Transaction::class)->make());
    });
  }
}
