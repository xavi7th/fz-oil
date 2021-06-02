<?php

namespace App\Modules\SuperAdmin\Tests\Feature;

use Tests\TestCase;
use App\Modules\SalesRep\Models\SalesRep;
use Illuminate\Foundation\Testing\WithFaker;
use App\Modules\Supervisor\Models\Supervisor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\SuperAdmin\Database\Seeders\StaffRoleTableSeeder;

class SalesRepTest extends TestCase
{
  use RefreshDatabase, WithFaker;

  public function setUp(): void
  {
    parent::setUp();

    $this->seed(StaffRoleTableSeeder::class);
  }

  /** @test  */
  public function sales_rep_can_login()
  {
    $this->withoutExceptionHandling();

    $sales_rep = SalesRep::factory()->create(['password' => 'pass']);

    $this->post(route('auth.login'), ['user_name' => $sales_rep->user_name, 'password' => 'pass', 'remember' => true])
      ->assertHeader('x-inertia-location', route('superadmin.dashboard'))
      ->assertStatus(409);
  }

  /** @test  */
  public function unauthorized_users_can_not_create_sales_rep()
  {
    // $this->withoutExceptionHandling();

    $this->assertCount(0, SalesRep::all());

    $this->actingAs(Supervisor::factory()->create(), 'supervisor')->post(route('salesrep.create'), $this->data_to_create_staff())->assertUnauthorized();
    $this->actingAs(FzAdmin::factory()->create(), 'fz_admin')->post(route('salesrep.create'), $this->data_to_create_staff())->assertUnauthorized();
    $this->actingAs(SalesRep::factory()->create(), 'sales_rep')->post(route('salesrep.create'), $this->data_to_create_staff())->assertUnauthorized();

    $this->assertCount(0, SalesRep::all());
  }

  /** @test  */
  public function unauthorized_users_can_not_view_sales_rep_list()
  {
    $this->actingAs(FzSupervisor::factory()->create(), 'fz_supervisor')->get(route('salesrep.list'))->assertUnauthorized();
    $this->actingAs(FzAdmin::factory()->create(), 'fz_admin')->get(route('salesrep.list'))->assertUnauthorized();
    $this->actingAs(SalesRep::factory()->create(), 'sales_rep')->get(route('salesrep.list'))->assertUnauthorized();
  }

}
