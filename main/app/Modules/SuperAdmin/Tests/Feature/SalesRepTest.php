<?php

namespace App\Modules\SuperAdmin\Tests\Feature;

use Tests\TestCase;
use App\Modules\SalesRep\Models\SalesRep;
use Illuminate\Foundation\Testing\WithFaker;
use App\Modules\FzCustomer\Models\FzCustomer;
use App\Modules\Supervisor\Models\Supervisor;
use App\Modules\FzStockManagement\Models\FzStock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\PurchaseOrder\Models\PurchaseOrder;
use App\Modules\FzStockManagement\Models\FzPriceBatch;
use App\Modules\FzStockManagement\Models\FzProductType;
use App\Modules\CompanyBankAccount\Models\CompanyBankAccount;
use App\Modules\SuperAdmin\Database\Seeders\StaffRoleTableSeeder;

class SalesRepTest extends TestCase
{
  use RefreshDatabase, WithFaker;

  private $sales_rep;

  public function setUp(): void
  {
    parent::setUp();

    $this->seed(StaffRoleTableSeeder::class);

    $this->sales_rep = SalesRep::factory()->verified()->active()->create(['password' => 'pass']);
  }

  /** @test  */
  public function sales_rep_can_login()
  {
    $this->withoutExceptionHandling();

    $this->post(route('auth.login'), ['user_name' => $this->sales_rep->user_name, 'password' => 'pass', 'remember' => true])
      ->assertHeader('x-inertia-location', route('salesrep.dashboard'))
      ->assertStatus(409);
  }

  /** @test  */
  public function unverified_sales_rep_will_be_required_to_set_a_password_on_login()
  {
    $this->withoutExceptionHandling();

    $sales_rep = SalesRep::factory()->active()->create(['password' => 'pass']);

    $this->post(route('auth.login'), ['user_name' => $sales_rep->user_name, 'password' => 'pass', 'remember' => true])
      ->assertRedirect(route('auth.login'))
      ->assertSessionHas('flash.action_required');
  }

  /** @test */
  public function unverified_sales_rep_can_set_password()
  {
    $this->withoutExceptionHandling();
    $sales_rep = SalesRep::factory()->active()->create();

    $this->post(route('auth.password'), ['email' => $sales_rep->email, 'password' => 'pass'])->assertSessionHas('flash.success', 'Password set successfully! Login using your new credentials.');
  }

  /** @test */
  public function password_is_required_for_sales_rep_to_set_password()
  {
    $sales_rep = SalesRep::factory()->active()->create(['password' => 'pass']);

    $this->post(route('auth.password'), ['email' => $sales_rep->email])->assertSessionHasErrors(['err' => 'A new password is required for your account.']);
  }

  /** @test */
  public function verified_sales_rep_cannot_set_password()
  {
    $sales_rep = SalesRep::factory()->verified()->active()->create(['password' => 'pass']);

    $this->post(route('auth.password'), ['email' => $sales_rep->email, 'password' => 'pass'])->assertSessionHas('flash.error', 'Unauthorised');
  }

  /** @test  */
  public function unauthorized_users_can_not_create_sales_rep()
  {
    SalesRep::truncate();
    $this->assertCount(0, SalesRep::all());

    $this->actingAs(Supervisor::factory()->create(), 'supervisor')->post(route('salesrep.create'), $this->data_to_create_staff())->assertRedirect(route('auth.login'));
    $this->assertCount(0, SalesRep::all());
    $this->actingAs(SalesRep::factory()->create(), 'sales_rep')->post(route('salesrep.create'), $this->data_to_create_staff())->assertRedirect(route('auth.login'));

    $this->assertCount(1, SalesRep::all());
  }

  /** @test */
  public function sales_rep_can_visit_dashboard()
  {
    $rsp = $this->actingAs($this->sales_rep, 'sales_rep')->get(route('salesrep.dashboard'))->assertOk();

    $page = $this->getResponseData($rsp);

    $this->assertArrayHasKey('errors', (array)$page->props);
    // $this->assertCount(19, (array)$page->props->sales_reps);
    $this->assertArrayHasKey('app', (array)$page->props);
    $this->assertArrayHasKey('routes', (array)$page->props);
    $this->assertArrayHasKey('isInertiaRequest', (array)$page->props);
    $this->assertArrayHasKey('auth', (array)$page->props);
    $this->assertArrayHasKey('flash', (array)$page->props);
  }

  /** @test */
  public function sales_rep_can_view_customners_list()
  {
    FzCustomer::factory()->count(19)->create();

    $rsp = $this->actingAs($this->sales_rep, 'sales_rep')->get(route('fzcustomer.list'))->assertOk();

    $page = $this->getResponseData($rsp);

    $this->assertArrayHasKey('fz_customers', (array)$page->props);
    $this->assertArrayHasKey('fz_customer_count', (array)$page->props);
    $this->assertArrayHasKey('fz_active_customer_count', (array)$page->props);
    $this->assertArrayHasKey('fz_suspended_customer_count', (array)$page->props);
    $this->assertArrayHasKey('fz_flagged_customer_count', (array)$page->props);
    $this->assertCount(19, (array)$page->props->fz_customers);
  }

  /** @test */
  public function unverified_sales_rep_can_not_view_customners_list()
  {
    $this->sales_rep->is_active = false;
    $this->sales_rep->save();
    $this->sales_rep->refresh();

    $this->actingAs($this->sales_rep, 'sales_rep')->get(route('fzcustomer.list'))->assertStatus(403);
  }

  /** @test */
  public function sales_rep_can_create_customner_account()
  {
    $this->assertDatabaseCount('fz_customers', 0);

    $this->actingAs($this->sales_rep, 'sales_rep')->post(route('fzcustomer.create', $this->data_to_create_customer()))
      ->assertRedirect(route('fzcustomer.list'))
      ->assertSessionHasNoErrors()
      ->assertSessionMissing('flash.error')
      ->assertSessionHas('flash.success', 'Customer account created. Transactions can be carried out for the user.');

    $this->assertDatabaseCount('fz_customers', 1);
  }

  /** @test */
  public function sales_rep_can_view_create_customner_purchase_order_page()
  {
    $this->withoutExceptionHandling();

    $customer = FzCustomer::factory()->active()->create();
    CompanyBankAccount::factory()->count(3)->create();
    FzPriceBatch::factory()->count(3)->create();

    $this->assertDatabaseCount('company_bank_accounts', 3);
    $this->assertDatabaseCount('fz_price_batches', 3);
    $this->assertNotNull(FzProductType::all());

    $rsp = $this->actingAs($this->sales_rep, 'sales_rep')->get(route('purchaseorders.create', $customer))->assertOk();
    $page = $this->getResponseData($rsp);

    $this->assertArrayHasKey('company_bank_accounts', (array)$page->props);
    $this->assertArrayHasKey('stock_types', (array)$page->props);
    $this->assertArrayHasKey('price_batches', (array)$page->props);
    $this->assertArrayHasKey('customer', (array)$page->props);
    // $this->assertCount(19, (array)$page->props->sales_reps);
  }

  /** @test */
  public function sales_rep_can_create_new_customner_purchase_order()
  {
    $this->withoutExceptionHandling();

    $customer = FzCustomer::factory()->create();

    FzStock::factory()->gallon()->create(['stock_quantity' => 150]);
    FzStock::factory()->count(3)->oil()->create(['stock_quantity' => 50]);

    $this->assertEquals(150, FzStock::gallon()->first()->stock_quantity);

    $this->actingAs($this->sales_rep, 'sales_rep')->post(route('purchaseorders.create', $customer), array_merge($this->data_to_create_customer_purchase_order(), ['fz_customer_id' => $customer->id, 'fz_product_type_id' => FzStock::oil()->first()->fz_product_type_id, 'fz_price_batch_id' => FzStock::oil()->first()->fz_price_batch_id, 'purchased_quantity' => 10, 'payment_type' => 'cash']))
      // ->dumpSession()
      ->assertRedirect(route('purchaseorders.list'))
      ->assertSessionHasNoErrors()
      ->assertSessionMissing('flash.error')
      ->assertSessionHas('flash.success', 'Customer\'s Purchase Order created.');

    $this->assertDatabaseCount('fz_customers', 1);
    $this->assertDatabaseCount('purchase_orders', 1);
    $this->assertTrue(FzCustomer::first()->purchase_orders()->first()->is(PurchaseOrder::first()));
    $this->assertTrue(PurchaseOrder::first()->buyer->is(FzCustomer::first()));
    $this->assertTrue(FzProductType::oil()->first()->purchase_orders()->first()->is(PurchaseOrder::first()));
    $this->assertTrue(FzStock::oil()->first()->purchase_orders()->first()->is(PurchaseOrder::first()));

    /** Assert stock reductions */
    $this->assertEquals(10, PurchaseOrder::first()->purchased_quantity);
    $this->assertEquals(40, PurchaseOrder::first()->fz_stock->stock_quantity);
    $this->assertEquals(140, FzStock::gallon()->first()->stock_quantity);
  }

  /** @test */
  public function sales_rep_can_create_customner_purchase_order_with_credit()
  {
    $this->withoutExceptionHandling();

    $customer = FzCustomer::factory()->create(['credit_limit' => 100000]);
    FzStock::factory()->count(3)->create(['fz_product_type_id' => 1, 'stock_quantity' => 50]);

    $this->assertDatabaseCount('fz_customers', 1);
    $this->assertEquals(100000, FzCustomer::first()->credit_limit);

    $this->actingAs($this->sales_rep, 'sales_rep')->post(route('purchaseorders.create', $customer), array_merge($this->data_to_create_customer_purchase_order(), ['fz_customer_id' => $customer->id, 'fz_product_type_id' => 1, 'payment_type' => 'credit', 'total_amount_paid' => 50000]))
      ->assertSessionHas('flash.success', 'Customer\'s Purchase Order created.');

    $this->assertDatabaseCount('fz_customers', 1);
    $this->assertEquals(50000, FzCustomer::first()->credit_limit);
  }

  /** @test */
  public function sales_rep_can_create_customner_purchase_order_swap_deal()
  {
    $this->withoutExceptionHandling();

    $customer = FzCustomer::factory()->create();
    FzStock::factory()->gallon()->create(['stock_quantity' => 150]);
    FzStock::factory()->oil()->count(3)->create(['stock_quantity' => 50]);

    $this->assertEquals(150, FzStock::gallon()->first()->stock_quantity);

    $request_data = array_merge($this->data_to_create_customer_purchase_order(), [
      'fz_customer_id' => $customer->id,
      'fz_product_type_id' => FzStock::oil()->first()->fz_product_type_id,
      'fz_price_batch_id' => FzStock::oil()->first()->fz_price_batch_id,
      'swap_product_type_id' => FzStock::gallon()->first()->fz_product_type_id,
      'purchased_quantity' => 10,
      'payment_type' => 'cash',
      'is_swap_purchase' => true,
      'swap_quantity' => 100,
    ]);

    $this->actingAs($this->sales_rep, 'sales_rep')->post(route('purchaseorders.create', $customer), $request_data)
      // ->dumpSession()
      ->assertSessionHas('flash.success', 'Customer\'s Purchase Order created.');

    $this->assertDatabaseCount('fz_customers', 1);
    /** Assert stock reductions increase by swap quantity and reduced by purchase quantity */
    $this->assertEquals(240, FzStock::gallon()->first()->stock_quantity);
  }

  /** @test */
  public function sales_rep_can_not_create_customner_purchase_order_for_flagged_customers()
  {
    // $this->withoutExceptionHandling();

    $this->assertDatabaseCount('fz_customers', 0);
    $customer = FzCustomer::factory()->flagged('he needs to update his contact info')->create();

    $this->actingAs($this->sales_rep, 'sales_rep')->post(route('purchaseorders.create', $customer), $this->data_to_create_customer_purchase_order())
      // ->dumpSession()
      ->assertSessionHasErrors(['fz_customer_id' => 'This customer\'s account is currently flagged because he needs to update his contact info. Contact your supervisor']);

    $this->assertDatabaseCount('fz_customers', 1);
  }

  /** @test */
  public function sales_rep_can_not_create_customner_purchase_order_for_suspended_customers()
  {
    // $this->withoutExceptionHandling();

    $this->assertDatabaseCount('fz_customers', 0);
    $customer = FzCustomer::factory()->suspended()->create();

    $this->actingAs($this->sales_rep, 'sales_rep')->post(route('purchaseorders.create', $customer), $this->data_to_create_customer_purchase_order())
      // ->dumpSession()
      ->assertSessionHasErrors(['fz_customer_id' => 'This customer\'s account is currently suspended and cannot make purchases. Contact your supervisor']);

    $this->assertDatabaseCount('fz_customers', 1);
  }

  /** @test */
  public function sales_rep_cannot_create_purchase_order_above_stock_quantity()
  {
    $customer = FzCustomer::factory()->create();

    FzStock::factory()->gallon()->create(['stock_quantity' => 150]);
    FzStock::factory()->count(3)->oil()->create(['stock_quantity' => 5]);

    $this->actingAs($this->sales_rep, 'sales_rep')->post(route('purchaseorders.create', $customer), array_merge($this->data_to_create_customer_purchase_order(), ['fz_customer_id' => $customer->id, 'fz_product_type_id' => FzStock::oil()->first()->fz_product_type_id, 'fz_price_batch_id' => FzStock::oil()->first()->fz_price_batch_id, 'purchased_quantity' => 10, 'payment_type' => 'cash']))
      // ->dumpSession()
      ->assertSessionHasErrors(['purchased_quantity' => 'Not enough products in stock to process this order']);

    $this->assertDatabaseCount('purchase_orders', 0);
  }

}
