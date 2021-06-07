<?php

namespace App\Modules\SuperAdmin\Tests\Feature;

use App\Modules\CompanyBankAccount\Models\CompanyBankAccount;
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

  private $super_admin;

  public function setUp(): void
  {
    parent::setUp();

    $this->seed(StaffRoleTableSeeder::class);

    $this->super_admin = SuperAdmin::factory()->create();
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
  public function super_admin_can_manage_company_bank_accounts()
  {
    $this->assertCount(0, CompanyBankAccount::all());

    /**
     * ? Test create account
     */
    $this->actingAs($this->super_admin, 'super_admin')->post( route('companybankaccount.create'), $this->data_to_create_bank_account())
      // ->dumpSession()
      ->assertSessionHasNoErrors()
      ->assertSessionMissing('flash.error')
      ->assertSessionHas('flash.success', 'Bank account created. Sales reps can naow record transactions for this bank.')
      ->assertRedirect(route('companybankaccount.index'));

    $this->assertCount(1, CompanyBankAccount::all());
    $bank_account = CompanyBankAccount::first();

    $this->assertTrue($bank_account->is_active);
    $bank = $bank_account->bank;

    /**
     * ? Test update account
     */

    $this->actingAs($this->super_admin, 'super_admin')->put( route('companybankaccount.update', $bank_account), array_merge($this->data_to_create_bank_account(), ['account_name' => 'Daniel Ose']))
      // ->dumpSession()
      ->assertSessionHasNoErrors()
      ->assertSessionMissing('flash.error')
      ->assertSessionHas('flash.success', 'Bank account updated. This update will reflect instantly.')
      ->assertRedirect(route('companybankaccount.index'));

    $bank_account->refresh();

    $this->assertDatabaseCount('company_bank_accounts', 1);
    $this->assertEquals('Daniel Ose', $bank_account->account_name);
    $this->assertEquals($bank, $bank_account->bank);

    /**
     * ? Test suspend account
     */
    $this->actingAs($this->super_admin, 'super_admin')->put( route('companybankaccount.suspend', $bank_account))
      // ->dumpSession()
      ->assertSessionHasNoErrors()
      ->assertSessionMissing('flash.error')
      ->assertSessionHas('flash.success', 'Bank account suspended. Transactions can no longer be recorded to this bank account.')
      ->assertRedirect(route('companybankaccount.index'));

    $bank_account->refresh();

    $this->assertFalse($bank_account->is_active);

    /**
     * ? Test activate account
     */
    $this->actingAs($this->super_admin, 'super_admin')->put( route('companybankaccount.activate', $bank_account))
      // ->dumpSession()
      ->assertSessionHasNoErrors()
      ->assertSessionMissing('flash.error')
      ->assertSessionHas('flash.success', 'Bank account activated. Transactions can now be recorded to this bank account.')
      ->assertRedirect(route('companybankaccount.index'));

    $bank_account->refresh();

    $this->assertTrue($bank_account->is_active);

    /**
     * ? Test view
     */
    $rsp = $this->actingAs($this->super_admin, 'super_admin')->get( route('companybankaccount.index'))->assertOk();
    $page = $this->getResponseData($rsp);

    $this->assertEquals('CompanyBankAccount::ManageAccounts', $page->component);
    $this->assertArrayHasKey('errors', (array)$page->props);
    $this->assertArrayHasKey('company_bank_accounts', (array)$page->props);
    $this->assertEquals(1, $page->props->company_bank_accounts_count);
    $this->assertArrayHasKey('can_create', (array)$page->props);
    $this->assertArrayHasKey('can_edit', (array)$page->props);
    $this->assertCount(1, (array)$page->props->company_bank_accounts);

    /**
     * ? Logout so we can try other users
     */
    $this->actingAs($this->super_admin, 'super_admin')->post( route('auth.logout'));

    /**
     * ? Authorization
     */
    $this->actingAs($supervisor = Supervisor::factory()->active()->verified()->create(), 'supervisor')->put(route('companybankaccount.activate', $bank_account))->assertStatus(403);
    $this->actingAs($supervisor, 'supervisor')->post( route('auth.logout', $bank_account));
    $this->actingAs(SalesRep::factory()->active()->verified()->create(), 'sales_rep')->put( route('companybankaccount.activate', $bank_account))->assertStatus(403);
  }
}
