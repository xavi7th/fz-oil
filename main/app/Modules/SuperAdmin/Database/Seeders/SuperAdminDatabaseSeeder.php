<?php

namespace App\Modules\SuperAdmin\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\SuperAdmin\Models\SuperAdmin;

class SuperAdminDatabaseSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    SuperAdmin::factory()->create([
      'full_name' => 'FZ',
      'email' => 'the-boss@fz.com',
      'password' => 'pass'
    ]);
  }
}
