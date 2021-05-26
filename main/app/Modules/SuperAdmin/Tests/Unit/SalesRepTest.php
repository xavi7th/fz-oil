<?php

namespace App\Modules\SuperAdmin\Tests\Unit;

use Tests\TestCase;
use App\Modules\SalesRep\Models\SalesRep;
use App\Modules\SuperAdmin\Models\StaffRole;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\SuperAdmin\Database\Seeders\StaffRoleTableSeeder;

class SalesRepTest extends TestCase
{
  use RefreshDatabase;

  public function setUp(): void
  {
    parent::setUp();

    $this->seed(StaffRoleTableSeeder::class);

    ray(StaffRole::all()->toArray());
  }

  /** @test  */
  public function sales_rep_can_login()
  {
    $this->withoutExceptionHandling();

    $sales_rep = SalesRep::factory()->create(['password' => 'pass']);

    $this->post(route('auth.login'), ['user_name' => $sales_rep->user_name, 'password' => 'pass', 'remember' => true])
      ->assertRedirect(route('salesrep.dashboard'));
  }
}
