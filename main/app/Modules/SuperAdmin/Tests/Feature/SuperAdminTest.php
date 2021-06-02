<?php

namespace App\Modules\SuperAdmin\Tests\Feature;

use Tests\TestCase;
use App\Modules\SalesRep\Models\SalesRep;
use App\Modules\SuperAdmin\Models\StaffRole;
use Illuminate\Foundation\Testing\WithFaker;
use App\Modules\SuperAdmin\Models\SuperAdmin;
use App\Modules\Supervisor\Models\Supervisor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\SuperAdmin\Database\Seeders\StaffRoleTableSeeder;

class SuperAdminTest extends TestCase
{
  use RefreshDatabase, WithFaker;

  public function setUp(): void
  {
    parent::setUp();

    $this->seed(StaffRoleTableSeeder::class);
  }

  /** @test  */
  public function super_admin_can_login()
  {
    $this->withoutExceptionHandling();

    $super_admin = SuperAdmin::factory()->create(['password' => 'pass']);

    $this->post(route('auth.login'), ['user_name' => $super_admin->user_name, 'password' => 'pass', 'remember' => true])
      ->assertHeader('x-inertia-location', route('superadmin.dashboard'))
      ->assertStatus(409);

    $this->flushSession();
  }

  /** @test  */
  public function super_admin_can_view_sales_reps()
  {
    SalesRep::factory()->count(19)->create();

    $rsp = $this->actingAs(SuperAdmin::factory()->create(), 'super_admin')->get(route('salesrep.list'))->assertOk();
    $page = $this->getResponseData($rsp);

    $this->assertArrayHasKey('sales_reps', (array)$page->props);
    $this->assertCount(19, (array)$page->props->sales_reps);
  }

  /** @test  */
  public function super_admin_can_create_sales_reps()
  {
    $this->assertCount(0, SalesRep::all());

    $this->actingAs(SuperAdmin::factory()->create(), 'super_admin')->post(route('salesrep.create'), $this->data_to_create_staff())
      // ->dumpSession()
      ->assertSessionHasNoErrors()
      ->assertSessionMissing('flash.error')
      ->assertSessionHas('flash.success', 'Sales Rep account created. Activate the account so the user can login')
      ->assertRedirect(route('salesrep.list'));

    $this->assertCount(1, SalesRep::all());
  }

  /** @test  */
  public function super_admin_can_view_supervisors()
  {
    Supervisor::factory()->count(19)->create();

    $rsp = $this->actingAs(SuperAdmin::factory()->create(), 'super_admin')->get(route('supervisor.list'))->assertOk();
    $page = $this->getResponseData($rsp);

    $this->assertArrayHasKey('supervisors', (array)$page->props);
    $this->assertCount(19, (array)$page->props->supervisors);
  }

  /** @test  */
  public function super_admin_can_create_supervisors()
  {
    $this->withoutExceptionHandling();

    $this->assertCount(0, Supervisor::all());

    $this->actingAs(SuperAdmin::factory()->create(), 'super_admin')->post(route('supervisor.create'), array_merge($this->data_to_create_staff(), ['staff_role_id' => StaffRole::supervisorId()]))
      ->assertSessionHasNoErrors()
      ->assertSessionMissing('flash.error')
      ->assertSessionHas('flash.success', 'Supervisor account created. Activate the account so the user can login')
      ->assertRedirect(route('supervisor.list'));

    $this->assertCount(1, Supervisor::all());
  }

}
