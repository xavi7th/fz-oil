<?php

namespace App\Modules\SuperAdmin\Tests\Feature;

use Tests\TestCase;
use App\Modules\SalesRep\Models\SalesRep;
use Illuminate\Foundation\Testing\WithFaker;
use App\Modules\FzCustomer\Models\FzCustomer;
use App\Modules\Supervisor\Models\Supervisor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\OfficeExpense\Models\OfficeExpense;
use App\Modules\CompanyBankAccount\Models\CompanyBankAccount;
use App\Modules\FzStockManagement\Models\FzPriceBatch;
use App\Modules\FzStockManagement\Models\FzProductType;
use App\Modules\FzStockManagement\Models\FzStock;
use App\Modules\SuperAdmin\Database\Seeders\StaffRoleTableSeeder;

class SupervisorTest extends TestCase
{
  use RefreshDatabase, WithFaker;

  private $supervisor;

  public function setUp(): void
  {
    parent::setUp();

    $this->seed(StaffRoleTableSeeder::class);
    $this->supervisor = Supervisor::factory()->active()->verified()->create(['password' => 'pass']);
  }

  /** @test  */
  public function supervisor_can_set_password_and_login()
  {
    /**
    * ? Test unverified require password
    */
    $supervisor = Supervisor::factory()->active()->create(['password' => 'pass']);
    $this->post(route('auth.login'), ['user_name' => $supervisor->user_name, 'password' => 'pass', 'remember' => true])
    ->assertRedirect(route('auth.login'))
    ->assertSessionHas('flash.action_required');

    /**
    * ? password is required to reset password
    */
    $this->post(route('auth.password'), ['email' => $supervisor->email])->assertSessionHasErrors(['err' => 'A new password is required for your account.']);
    /**
    * ? unverified is required to reset password
    */
    $this->post(route('auth.password'), ['email' => $this->supervisor->email, 'password' => 'pass'])->assertSessionHas('flash.error', 'Unauthorised');

    /**
    * ? unverified can reset password
    */
    $this->post(route('auth.password'), ['email' => $supervisor->email, 'password' => 'pass'])->assertSessionHas('flash.success', 'Password set successfully! Login using your new credentials.');

    /**
    * ? Test proper login
    */
    $this->post(route('auth.login'), ['user_name' => $this->supervisor->user_name, 'password' => 'pass', 'remember' => true])
    // ->dumpSession()
    ->assertHeader('x-inertia-location', route('supervisor.dashboard'))
    ->assertStatus(409);
  }

  /** @test  */
  public function supervisors_can_manage_sales_reps()
  {
    // $this->withoutExceptionHandling();

    /**
    * ? test create
    */
    $this->assertCount(0, SalesRep::all());

    $this->actingAs($this->supervisor, 'supervisor')->post(route('salesrep.create'), $this->data_to_create_staff())
    // ->dumpSession()
    ->assertSessionHasNoErrors()
    ->assertSessionMissing('flash.error')
    ->assertSessionHas('flash.success', 'Sales Rep account created. Activate the account so the user can login')
    ->assertRedirect(route('salesrep.list'));

    $this->assertCount(1, SalesRep::all());

    /**
    * ? test update
    */
    $sales_rep = SalesRep::first();
    $full_name = $sales_rep->full_name;

    $this->actingAs($this->supervisor, 'supervisor')->put(route('salesrep.update', $sales_rep), array_merge($sales_rep->toArray(), ['email' => 'x@y.com']))
    // ->dumpSession()
    ->assertSessionHasNoErrors()
    ->assertSessionMissing('flash.error')
    ->assertSessionHas('flash.success', 'Sales Rep account updated.')
    ->assertRedirect(route('salesrep.list'));

    $sales_rep->refresh();

    $this->assertEquals('x@y.com', $sales_rep->email);
    $this->assertEquals($full_name, $sales_rep->full_name);

    /**
    * ? test activate
    */

    $this->assertFalse($sales_rep->is_active);

    $this->actingAs($this->supervisor, 'supervisor')->put(route('salesrep.activate', $sales_rep))
    // ->dumpSession()
    ->assertSessionHasNoErrors()
    ->assertSessionMissing('flash.error')
    ->assertSessionHas('flash.success', 'Sales Rep account activated.')
    ->assertRedirect(route('salesrep.list'));

    $sales_rep->refresh();

    $this->assertTrue($sales_rep->is_active);


    /**
    * ? test suspend
    */
    $this->actingAs($this->supervisor, 'supervisor')->put(route('salesrep.suspend', $sales_rep))
    // ->dumpSession()
    ->assertSessionHasNoErrors()
    ->assertSessionMissing('flash.error')
    ->assertSessionHas('flash.success', 'Sales Rep account suspended. They will no longer be able to login')
    ->assertRedirect(route('salesrep.list'));

    $sales_rep->refresh();

    $this->assertFalse($sales_rep->is_active);

    /**
    * ? test view list
    */
    SalesRep::factory()->count(19)->create();

    $rsp = $this->actingAs($this->supervisor, 'supervisor')->get(route('salesrep.list'))->assertOk();
    $page = $this->getResponseData($rsp);

    $this->assertArrayHasKey('sales_reps', (array)$page->props);
    $this->assertCount(20, (array)$page->props->sales_reps);
  }

  /** @test */
  public function supervisor_can_manage_customer_accounts()
  {
    // $this->withoutExceptionHandling();

    $customer = FzCustomer::factory()->create();

    $this->assertTrue($customer->is_active);

    $this->actingAs($this->supervisor, 'supervisor')->put(route('fzcustomer.suspend', $customer))
    // ->dumpSession()
    ->assertRedirect()
    ->assertSessionHasNoErrors()
    ->assertSessionMissing('flash.error')
    ->assertSessionHas('flash.success', 'Customer\'s account has been suspended. The sales rep will be notified to contact their supervisor on next customer transaction.');

    $customer->refresh();

    $this->assertFalse($customer->is_active);

    $this->actingAs($this->supervisor, 'supervisor')->put(route('fzcustomer.activate', $customer))
    // ->dumpSession()
    ->assertRedirect()
    ->assertSessionHasNoErrors()
    ->assertSessionMissing('flash.error')
    ->assertSessionHas('flash.success', 'Customer\'s account has been activated. They can resume transactions without issues.');

    $customer->refresh();

    $this->assertTrue($customer->is_active);

    /**
    * ? test update
    */
    $full_name = $customer->full_name;

    $this->actingAs($this->supervisor, 'supervisor')->put(route('fzcustomer.update', $customer), array_merge($customer->toArray(), ['email' => 'x@y.com']))
    // ->dumpSession()
    ->assertRedirect(route('fzcustomer.list'))
    ->assertSessionHasNoErrors()
    ->assertSessionMissing('flash.error')
    ->assertSessionHas('flash.success', 'Customer\'s details updated.');

    $customer->refresh();

    $this->assertEquals('x@y.com', $customer->email);
    $this->assertEquals($full_name, $customer->full_name);
  }

  /** @test */
  public function supervisor_can_view_expense_list()
  {
    CompanyBankAccount::factory()->create();
    OfficeExpense::factory()->count(20)->create();

    $rsp = $this->actingAs($this->supervisor, 'supervisor')->get(route('officeexpense.list'))->assertOk();
    $page = $this->getResponseData($rsp);

    $this->assertEquals('OfficeExpense::ManageOfficeExpenses', $page->component);
    $this->assertArrayHasKey('errors', (array)$page->props);
    $this->assertArrayHasKey('office_expenses', (array)$page->props);
    $this->assertArrayHasKey('office_expenses_count', (array)$page->props);
    $this->assertCount(20, (array)$page->props->office_expenses);
    $this->assertEquals(20, $page->props->office_expenses_count);
  }

  /** @test */
  public function supervisor_can_manage_stock()
  {
    /**
    * ? Test create and that gallon will be created
    */

    $this->assertDatabaseCount('fz_stock', 0);
    $this->actingAs($this->supervisor, 'supervisor')->post(route('fzstock.create'), $this->data_to_create_stock())
    // ->dumpSession()
    ->assertRedirect(route('fzstock.list'))
    ->assertSessionHasNoErrors()
    ->assertSessionMissing('flash.error')
    ->assertSessionHas('flash.success', 'Stock has been created / updated.');

    /**
     * ! One for gallon and one for the oil we created
     */
    $this->assertDatabaseCount('fz_stock', 2);
    $fz_stock = FzStock::oil()->first();
    $fz_gallon_stock = FzStock::gallon()->first();

    $this->assertEquals(100, $fz_stock->stock_quantity);
    $this->assertEquals(100, $fz_gallon_stock->stock_quantity);

    /**
    * ? Test update on create existing product
    */
    $this->actingAs($this->supervisor, 'supervisor')->post(route('fzstock.create'), array_merge($this->data_to_create_stock(), ['fz_product_type_id' => $fz_stock->fz_product_type_id, 'fz_price_batch_id' => $fz_stock->fz_price_batch_id]))
    // ->dumpSession()
    ->assertRedirect(route('fzstock.list'))
    ->assertSessionHasNoErrors()
    ->assertSessionMissing('flash.error')
    ->assertSessionHas('flash.success', 'Stock has been created / updated.');

    $fz_stock->refresh();
    $fz_gallon_stock->refresh();

    $this->assertDatabaseCount('fz_stock', 2);
    $this->assertEquals(200, $fz_stock->stock_quantity);
    $this->assertEquals(200, $fz_gallon_stock->stock_quantity);


    /**
    * ? Test update
    */
    $this->actingAs($this->supervisor, 'supervisor')->put(route('fzstock.update', $fz_stock), ['update_selling_price' => true, 'selling_price' => 3000])
    ->dumpSession()
    ->assertRedirect(route('fzstock.list'))
    ->assertSessionHasNoErrors()
    ->assertSessionMissing('flash.error')
    ->assertSessionHas('flash.success', 'Stock has been updated.');

    $fz_stock->refresh();
    $fz_gallon_stock->refresh();

    $this->assertDatabaseCount('fz_stock', 2);
    $this->assertEquals(200, $fz_stock->stock_quantity);
    $this->assertEquals(200, $fz_gallon_stock->stock_quantity);
    $this->assertEquals(3000, $fz_stock->price_batch->selling_price);

    $this->actingAs($this->supervisor, 'supervisor')->put(route('fzstock.update', $fz_stock), ['update_stock_quantity' => true, 'stock_quantity' => 100])
    // ->dumpSession()
    ->assertRedirect(route('fzstock.list'))
    ->assertSessionHasNoErrors()
    ->assertSessionMissing('flash.error')
    ->assertSessionHas('flash.success', 'Stock has been updated.');

    $fz_stock->refresh();
    $fz_gallon_stock->refresh();

    $this->assertDatabaseCount('fz_stock', 2);
    $this->assertEquals(300, $fz_stock->stock_quantity);
    $this->assertEquals(300, $fz_gallon_stock->stock_quantity);
    $this->assertEquals(3000, $fz_stock->price_batch->selling_price);

    $this->actingAs($this->supervisor, 'supervisor')->put(route('fzstock.update', $fz_stock), ['update_stock_quantity' => true,'update_selling_price' => true])
    ->assertSessionHasErrors([
      'selling_price' => 'The selling price field is required.',
      'stock_quantity' => 'The stock quantity field is required.',
    ]);

    /**
     * ? Test create price batch and stock
     */

    $this->assertDatabaseCount('fz_price_batches', 2);

    $this->actingAs($this->supervisor, 'supervisor')->post(route('fzstock.create', $fz_stock), array_merge($this->data_to_create_price_batch_and_stock(), ['fz_product_type_id' => $fz_stock->fz_product_type_id]))
    // ->dumpSession()
    ->assertRedirect(route('fzstock.list'))
    ->assertSessionHasNoErrors()
    ->assertSessionMissing('flash.error')
    ->assertSessionHas('flash.success', 'Stock has been created / updated.');

    $fz_stock->refresh();
    $fz_gallon_stock->refresh();

    $this->assertDatabaseCount('fz_stock', 3);
    $this->assertDatabaseCount('fz_price_batches', 3);
    $this->assertEquals(300, $fz_stock->stock_quantity);
    $this->assertEquals(400, $fz_gallon_stock->stock_quantity);
    $this->assertEquals(100, FzStock::oil()->latest('id')->first()->stock_quantity);


    /**
     * ? Test cannot create duplicate price batch for stock
     */
    $this->actingAs($this->supervisor, 'supervisor')->post(route('fzstock.create', $fz_stock), array_merge($this->data_to_create_price_batch_and_stock(), ['fz_product_type_id' => $fz_stock->fz_product_type_id]))
    // ->dumpSession()
    ->assertSessionHasErrors('set_new_price_batch', 'A price batch with this cost and selling prrice already exists for this stock type. Add the items to that price batch instead');

    $fz_stock->refresh();
    $fz_gallon_stock->refresh();

    $this->assertDatabaseCount('fz_stock', 3);
    $this->assertDatabaseCount('fz_price_batches', 3);
    $this->assertEquals(300, $fz_stock->stock_quantity);
    $this->assertEquals(400, $fz_gallon_stock->stock_quantity);
    $this->assertEquals(100, FzStock::latest('id')->first()->stock_quantity);


    /**
    * ? Test view
    */
    $rsp = $this->actingAs($this->supervisor, 'supervisor')->get(route('fzstock.list'))->assertOk();
    $page = $this->getResponseData($rsp);

    $this->assertEquals('FzStockManagement::ManageProductBatches', $page->component);
    $this->assertArrayHasKey('errors', (array)$page->props);
    $this->assertArrayHasKey('fz_stock', (array)$page->props);
    $this->assertArrayHasKey('fz_stock_count', (array)$page->props);
    $this->assertArrayHasKey('stock_types', (array)$page->props);
    $this->assertArrayHasKey('price_batches', (array)$page->props);
    $this->assertArrayHasKey('can_create_stock', (array)$page->props);
    $this->assertArrayHasKey('can_edit_stock', (array)$page->props);
    $this->assertCount(3, (array)$page->props->fz_stock);

    $rsp = $this->actingAs($this->supervisor, 'supervisor')->get(route('auth.logout'));


    $this->actingAs(SalesRep::factory()->active()->verified()->create(), 'sales_rep')->post(route('fzstock.create'), $this->data_to_create_stock())->assertStatus(403);
  }
}
