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
  public function super_admin_can_edit_sales_reps()
  {
    $this->withoutExceptionHandling();

    $sales_rep = SalesRep::factory()->create();
    $full_name = $sales_rep->full_name;

    $this->actingAs(SuperAdmin::factory()->create(), 'super_admin')->put(route('salesrep.update', $sales_rep), array_merge($sales_rep->toArray(), ['email' => 'x@y.com']))
      // ->dumpSession()
      ->assertSessionHasNoErrors()
      ->assertSessionMissing('flash.error')
      ->assertSessionHas('flash.success', 'Sales Rep account updated.')
      ->assertRedirect(route('salesrep.list'));

    $sales_rep->refresh();

    $this->assertEquals('x@y.com', $sales_rep->email);
    $this->assertEquals($full_name, $sales_rep->full_name);
  }

  /** @test  */
  public function super_admin_can_suspend_a_sales_rep_account()
  {
    $this->withoutExceptionHandling();

    $sales_rep = SalesRep::factory()->active()->create();

    $this->assertTrue($sales_rep->is_active);

    $this->actingAs(SuperAdmin::factory()->create(), 'super_admin')->put(route('salesrep.suspend', $sales_rep))
      // ->dumpSession()
      ->assertSessionHasNoErrors()
      ->assertSessionMissing('flash.error')
      ->assertSessionHas('flash.success', 'Sales Rep account suspended. They will no longer be able to login')
      ->assertRedirect(route('salesrep.list'));

    $sales_rep->refresh();

    $this->assertFalse($sales_rep->is_active);
  }

  /** @test  */
  public function super_admin_can_delete_a_sales_rep_account()
  {
    $this->withoutExceptionHandling();

    $sales_rep = SalesRep::factory()->active()->create();

    $this->actingAs(SuperAdmin::factory()->create(), 'super_admin')->delete(route('salesrep.delete', $sales_rep))
      // ->dumpSession()
      ->assertSessionHasNoErrors()
      ->assertSessionMissing('flash.error')
      ->assertSessionHas('flash.success', 'Sales Rep account permanently deleted.')
      ->assertRedirect(route('salesrep.list'));

    $this->assertSoftDeleted($sales_rep);
  }

  /** @test  */
  public function super_admin_can_activate_a_sales_rep_account()
  {
    $this->withoutExceptionHandling();

    $sales_rep = SalesRep::factory()->create();

    $this->assertFalse($sales_rep->is_active);

    $this->actingAs(SuperAdmin::factory()->create(), 'super_admin')->put(route('salesrep.activate', $sales_rep))
      // ->dumpSession()
      ->assertSessionHasNoErrors()
      ->assertSessionMissing('flash.error')
      ->assertSessionHas('flash.success', 'Sales Rep account activated.')
      ->assertRedirect(route('salesrep.list'));

    $sales_rep->refresh();

    $this->assertTrue($sales_rep->is_active);
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


  /** @test  */
  public function super_admin_can_edit_supervisors_details()
  {
    $this->withoutExceptionHandling();

    $supervisor = Supervisor::factory()->create();
    $full_name = $supervisor->full_name;

    $this->actingAs(SuperAdmin::factory()->create(), 'super_admin')->put(route('supervisor.update', $supervisor), array_merge($supervisor->toArray(), ['email' => 'x@y.com']))
      // ->dumpSession()
      ->assertSessionHasNoErrors()
      ->assertSessionMissing('flash.error')
      ->assertSessionHas('flash.success', 'Supervisor account updated.')
      ->assertRedirect(route('supervisor.list'));

    $supervisor->refresh();

    $this->assertEquals('x@y.com', $supervisor->email);
    $this->assertEquals($full_name, $supervisor->full_name);
  }

  /** @test  */
  public function super_admin_can_suspend_a_supervisor_account()
  {
    $this->withoutExceptionHandling();

    $supervisor = Supervisor::factory()->active()->create();

    $this->assertTrue($supervisor->is_active);

    $this->actingAs(SuperAdmin::factory()->create(), 'super_admin')->put(route('supervisor.suspend', $supervisor))
      // ->dumpSession()
      ->assertSessionHasNoErrors()
      ->assertSessionMissing('flash.error')
      ->assertSessionHas('flash.success', 'Supervisor account suspended. They will no longer be able to login')
      ->assertRedirect(route('supervisor.list'));

    $supervisor->refresh();

    $this->assertFalse($supervisor->is_active);
  }

  /** @test  */
  public function super_admin_can_delete_a_supervisor_account()
  {
    $this->withoutExceptionHandling();

    $supervisor = Supervisor::factory()->active()->create();

    $this->actingAs(SuperAdmin::factory()->create(), 'super_admin')->delete(route('supervisor.delete', $supervisor))
      // ->dumpSession()
      ->assertSessionHasNoErrors()
      ->assertSessionMissing('flash.error')
      ->assertSessionHas('flash.success', 'Supervisor account permanently deleted.')
      ->assertRedirect(route('supervisor.list'));

    $this->assertSoftDeleted($supervisor);
  }

  /** @test */
  public function super_admin_can_flag_customer_account()
  {
    $this->markTestSkipped('To be implemented');
  }

}
