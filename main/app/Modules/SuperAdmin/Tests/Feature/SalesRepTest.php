<?php

namespace App\Modules\SuperAdmin\Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use App\Modules\SalesRep\Models\SalesRep;
use Illuminate\Foundation\Testing\WithFaker;
use App\Modules\FzCustomer\Models\FzCustomer;
use App\Modules\Supervisor\Models\Supervisor;
use App\Modules\FzStockManagement\Models\FzStock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\PurchaseOrder\Models\CashLodgement;
use App\Modules\PurchaseOrder\Models\PurchaseOrder;
use App\Modules\FzStockManagement\Models\FzPriceBatch;
use App\Modules\FzStockManagement\Models\FzProductType;
use App\Modules\CompanyBankAccount\Models\CompanyBankAccount;
use App\Modules\FzCustomer\Models\CreditTransaction;
use App\Modules\OfficeExpense\Models\OfficeExpense;
use App\Modules\SuperAdmin\Database\Seeders\StaffRoleTableSeeder;
use Str;

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

    $this->assertEquals('FzCustomer::ManageCustomers', $page->component);
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

    $customer = FzCustomer::factory()->create();
    CompanyBankAccount::factory()->count(3)->create();
    FzPriceBatch::factory()->count(3)->create();

    $this->assertDatabaseCount('company_bank_accounts', 3);
    $this->assertDatabaseCount('fz_price_batches', 3);
    $this->assertNotNull(FzProductType::all());

    $rsp = $this->actingAs($this->sales_rep, 'sales_rep')->get(route('purchaseorders.create', $customer))->assertOk();
    $page = $this->getResponseData($rsp);

    $this->assertEquals('PurchaseOrder::Create', $page->component);
    $this->assertArrayHasKey('company_bank_accounts', (array)$page->props);
    $this->assertArrayHasKey('stock_types', (array)$page->props);
    $this->assertArrayHasKey('price_batches', (array)$page->props);
    $this->assertArrayHasKey('customer', (array)$page->props);
    // $this->assertCount(19, (array)$page->props->sales_reps);
  }

  /** @test */
  public function sales_rep_can_create_new_customner_purchase_order()
  {
    // $this->withoutExceptionHandling();

    $customer = FzCustomer::factory()->create(['credit_limit' => 100000000000000, 'credit_balance' => 10000000000000]);

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
  public function a_credit_transaction_will_be_created_on_credit_purchase()
  {
    // $this->withoutExceptionHandling();

    $customer = FzCustomer::factory()->create(['credit_limit' => 100000000000000, 'credit_balance' => 10000000000000]);
    $old_balance = $customer->credit_balance;
    $old_limit = $customer->credit_limit;

    FzStock::factory()->gallon()->create(['stock_quantity' => 150]);
    FzStock::factory()->count(3)->oil()->create(['stock_quantity' => 50]);

    $this->assertEquals(150, FzStock::gallon()->first()->stock_quantity);

    $request_data = array_merge(
      $this->data_to_create_customer_purchase_order(),
      [
        'fz_customer_id' => $customer->id,
        'fz_product_type_id' => FzStock::oil()->first()->fz_product_type_id,
        'fz_price_batch_id' => FzStock::oil()->first()->fz_price_batch_id,
        'purchased_quantity' => 10,
        'payment_type' => 'credit',
        'total_amount_paid' => 30000
      ]
    );

    $this->actingAs($this->sales_rep, 'sales_rep')->post(route('purchaseorders.create', $customer), $request_data)
      // ->dumpSession()
      ->assertSessionHas('flash.success', 'Customer\'s Purchase Order created.');

    $customer->refresh();

    $this->assertDatabaseCount('fz_customers', 1);
    $this->assertDatabaseCount('credit_transactions', 1);
    $this->assertEquals(number_format($old_balance - 30000), number_format($customer->credit_balance));
    $this->assertEquals(number_format($old_limit), number_format($customer->credit_limit));
    $this->assertTrue(FzCustomer::first()->credit_transactions()->first()->is(CreditTransaction::first()));
    $this->assertTrue(CreditTransaction::first()->customer->is(FzCustomer::first()));
  }

  /** @test */
  public function sales_rep_can_create_customer_purchase_order_with_credit()
  {
    // $this->withoutExceptionHandling();

    $customer = FzCustomer::factory()->create(['credit_limit' => 100000000000000, 'credit_balance' => 10000000000000]);
    FzStock::factory()->gallon()->create(['stock_quantity' => 150]);
    FzStock::factory()->count(3)->oil()->create(['stock_quantity' => 50]);

    $this->assertDatabaseCount('fz_customers', 1);
    $this->assertEquals(10000000000000, FzCustomer::first()->credit_balance);

    $this->actingAs($this->sales_rep, 'sales_rep')->post(route('purchaseorders.create', $customer), array_merge(
      $this->data_to_create_customer_purchase_order(),
      [
        'fz_customer_id' => $customer->id,
        'fz_product_type_id' => FzStock::oil()->first()->fz_product_type_id,
        'fz_price_batch_id' => FzStock::oil()->first()->fz_price_batch_id,
        'payment_type' => 'credit', 'total_amount_paid' => 50000
      ]
    ))
    // ->dumpSession()
      ->assertSessionHas('flash.success', 'Customer\'s Purchase Order created.');

    $this->assertDatabaseCount('fz_customers', 1);
    $this->assertEquals(10000000000000, FzCustomer::first()->credit_balance + 50000);
  }

  /** @test */
  public function sales_rep_can_not_create_customner_purchase_order_with_credit_above_credit_limit()
  {
    // $this->withoutExceptionHandling();

    $customer = FzCustomer::factory()->create(['credit_balance' => 20000, 'credit_limit' => 20000]);
    FzStock::factory()->gallon()->create(['stock_quantity' => 150]);
    FzStock::factory()->count(3)->oil()->create(['stock_quantity' => 50]);

    $this->assertDatabaseCount('fz_customers', 1);
    $this->assertEquals(20000, FzCustomer::first()->credit_balance);


    $this->actingAs($this->sales_rep, 'sales_rep')->post(route('purchaseorders.create', $customer), array_merge($this->data_to_create_customer_purchase_order(), ['total_amount_paid' => 100000, 'fz_customer_id' => $customer->id, 'fz_product_type_id' => FzStock::oil()->first()->fz_product_type_id, 'fz_price_batch_id' => FzStock::oil()->first()->fz_price_batch_id, 'purchased_quantity' => 10, 'payment_type' => 'credit']))
      // ->dumpSession()
      ->assertSessionHasErrors(['total_amount_paid' => 'This customer\'s credit balance is not up to 100000']);

    $this->assertDatabaseCount('fz_customers', 1);
    $this->assertEquals(20000, FzCustomer::first()->credit_balance);
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
  public function sales_rep_can_not_create_customner_purchase_order_for_suspended_bank_accounts()
  {
    $this->assertDatabaseCount('fz_customers', 0);
    $customer = FzCustomer::factory()->create();
    $company_bank_account = CompanyBankAccount::factory()->suspended()->create();

    $this->actingAs($this->sales_rep, 'sales_rep')->post(route('purchaseorders.create', $customer), array_merge($this->data_to_create_customer_purchase_order(), ['company_bank_account_id' => $company_bank_account->id]))
      // ->dumpSession()
      ->assertSessionHasErrors(['company_bank_account_id' => 'This bank account has been suspended from use']);

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

  /** @test */
  public function sales_rep_can_create_cash_lodgement()
  {
    // $this->withoutExceptionHandling();
    $company_bank_account = CompanyBankAccount::factory()->create();
    ray(CreditTransaction::factory()->cash()->repayment()->create(['amount' => 500000, 'is_lodged' => false]));

    $this->actingAs($this->sales_rep, 'sales_rep')->post(route('purchaseorders.cashlodgement.create'), ['amount' => 300000, 'company_bank_account_id' => $company_bank_account->id, 'lodgement_date' => now(), 'teller' => UploadedFile::fake()->image('teller.jpg')])
      // ->dumpSession()
      ->assertRedirect(route('purchaseorders.cashlodgement.create'))
      ->assertSessionHasNoErrors()
      ->assertSessionMissing('flash.error')
      ->assertSessionHas('flash.success', 'Cash lodgement record created.');

    $this->assertCount(1, $company_bank_account->cash_lodgements);
    $this->assertTrue(CashLodgement::first()->bank()->is($company_bank_account));
    $this->assertTrue(Str::contains(CashLodgement::first()->teller_url, '/storage/cash-lodgement-tellers/'));
    $this->assertEquals(300000, $company_bank_account->cash_lodgements()->first()->amount);
    $this->assertTrue(CompanyBankAccount::first()->cash_lodgements()->first()->is(CashLodgement::first()));
  }

  /** @test */
  public function sales_rep_cannot_create_cash_lodgement_to_a_suspended_bank()
  {
    $company_bank_account = CompanyBankAccount::factory()->suspended()->create();

    $this->actingAs($this->sales_rep, 'sales_rep')->post(route('purchaseorders.cashlodgement.create'), ['amount' => 300000, 'company_bank_account_id' => $company_bank_account->id, 'lodgement_date' => now()])
      // ->dumpSession()
      ->assertSessionHasErrors(['company_bank_account_id' => 'This bank account has been suspended from use']);

    $this->assertCount(0, $company_bank_account->cash_lodgements);
  }

  /** @test */
  public function sales_rep_can_view_cash_lodgement_list()
  {
    CompanyBankAccount::factory()->create();
    CashLodgement::factory()->count(20)->create();

    $rsp = $this->actingAs($this->sales_rep, 'sales_rep')->get(route('purchaseorders.cashlodgement.create'))->assertOk();
    $page = $this->getResponseData($rsp);

    $this->assertEquals('PurchaseOrder::CashLodgements', $page->component);
    $this->assertArrayHasKey('errors', (array)$page->props);
    $this->assertArrayHasKey('cash_lodgements', (array)$page->props);
    $this->assertArrayHasKey('cash_lodgements_count', (array)$page->props);
    $this->assertCount(20, (array)$page->props->cash_lodgements);
    $this->assertEquals(20, $page->props->cash_lodgements_count);
  }

  /** @test */
  public function unverified_sales_rep_can_not_view_cash_lodgement_list()
  {
    $this->sales_rep->verified_at = null;
    $this->sales_rep->save();

    $this->actingAs($this->sales_rep, 'sales_rep')->get(route('purchaseorders.cashlodgement.create'))->assertStatus(403);
  }

  /** @test */
  public function suspended_sales_rep_can_not_view_cash_lodgement_list()
  {
    $this->sales_rep->is_active = false;
    $this->sales_rep->save();

    $this->actingAs($this->sales_rep, 'sales_rep')->get(route('purchaseorders.cashlodgement.create'))->assertStatus(403);
  }

  /** @test */
  public function sales_rep_cannot_create_cash_lodgements_above_cash_in_office()
  {
    $company_bank_account = CompanyBankAccount::factory()->suspended()->create();

    $this->actingAs($this->sales_rep, 'sales_rep')->post(route('purchaseorders.cashlodgement.create'), ['amount' => 300000, 'company_bank_account_id' => $company_bank_account->id, 'lodgement_date' => now()])
      // ->dumpSession()
      ->assertSessionHasErrors(['amount' => 'There is not enough cash in the office to make this cash lodgement.']);
  }

  /** @test */
  public function sales_rep_can_create_expenses()
  {
    // $this->withoutExceptionHandling();
    ray(CreditTransaction::factory()->cash()->repayment()->create(['amount' => 30000, 'is_lodged' => false]));

    $this->assertDatabaseCount('office_expenses', 0);

    $this->actingAs($this->sales_rep, 'sales_rep')->post(route('officeexpense.create'), array_merge($this->data_to_create_expense(), ['amount' => 20000]))
      // ->dumpSession()
      ->assertRedirect(route('officeexpense.list'))
      ->assertSessionHasNoErrors()
      ->assertSessionMissing('flash.error')
      ->assertSessionHas('flash.success', 'Office Expense created.');

    $this->assertDatabaseCount('office_expenses', 1);
    $this->assertTrue(SalesRep::first()->expenses()->first()->is(OfficeExpense::first()));
    $this->assertTrue(OfficeExpense::first()->sales_rep->is(SalesRep::first()));
    $this->assertEquals(20000, OfficeExpense::first()->amount);
    $this->assertEquals('transfer', OfficeExpense::first()->payment_type);
  }

  /** @test */
  public function sales_rep_cannot_create_expenses_above_cash_in_office()
  {
    // $this->withoutExceptionHandling();
    PurchaseOrder::factory()->count(5)->cash()->create(['is_swap_transaction' => $this->faker->randomElement([true, false]), 'total_amount_paid' => 3000]);

    $this->assertDatabaseCount('office_expenses', 0);

    $this->actingAs($this->sales_rep, 'sales_rep')->post(route('officeexpense.create'), array_merge($this->data_to_create_expense(), ['payment_type' => 'cash', 'amount' => 10000000000]))
      // ->dumpSession()
      ->assertSessionHasErrors(['amount' => 'There is not enough cash in the office to fund this expense']);

    $this->assertDatabaseCount('office_expenses', 0);
  }

  /** @test */
  public function sales_rep_can_view_expense_list()
  {
    CompanyBankAccount::factory()->create();
    OfficeExpense::factory()->count(20)->create();

    $rsp = $this->actingAs($this->sales_rep, 'sales_rep')->get(route('officeexpense.list'))->assertOk();
    ray($page = $this->getResponseData($rsp));

    $this->assertEquals('OfficeExpense::ManageOfficeExpenses', $page->component);
    $this->assertArrayHasKey('errors', (array)$page->props);
    $this->assertArrayHasKey('office_expenses', (array)$page->props);
    $this->assertArrayHasKey('office_expenses_count', (array)$page->props);
    $this->assertCount(20, (array)$page->props->office_expenses);
    $this->assertEquals(20, $page->props->office_expenses_count);
  }

  /** @test */
  public function unverified_sales_rep_can_not_view_expenses_list()
  {
    $this->sales_rep->verified_at = null;
    $this->sales_rep->save();

    $this->actingAs($this->sales_rep, 'sales_rep')->get(route('officeexpense.list'))->assertStatus(403);
  }

  /** @test */
  public function suspended_sales_rep_can_not_view_expenses_list()
  {
    $this->sales_rep->is_active = false;
    $this->sales_rep->save();

    $this->actingAs($this->sales_rep, 'sales_rep')->get(route('officeexpense.list'))->assertStatus(403);
  }

  /** @test */
  public function sales_rep_can_create_credit_repayment()
  {
    // $this->withoutExceptionHandling();
    $customer = FzCustomer::factory()->create(['credit_limit' => 100000000000000, 'credit_balance' => 10000000000000]);

    FzStock::factory()->gallon()->create(['stock_quantity' => 150]);
    FzStock::factory()->count(3)->oil()->create(['stock_quantity' => 50]);

    $this->assertEquals(150, FzStock::gallon()->first()->stock_quantity);

    $this->actingAs($this->sales_rep, 'sales_rep')->post(route('purchaseorders.create', $customer), array_merge($this->data_to_create_customer_purchase_order(), ['fz_customer_id' => $customer->id, 'fz_product_type_id' => FzStock::oil()->first()->fz_product_type_id, 'fz_price_batch_id' => FzStock::oil()->first()->fz_price_batch_id, 'purchased_quantity' => 10, 'payment_type' => 'cash']));
      // ->dumpSession();

    $this->actingAs($this->sales_rep, 'sales_rep')->post(route('fzcustomer.credit_transactions.repayment', $customer), array_merge($this->data_to_create_credit_repayment(), ['amount' => 20000, 'recorded_by' => $this->sales_rep->id, 'trans_date' => now()->subDays(5)]))
    // ->dumpSession()
    ->assertRedirect(route('fzcustomer.credit_transactions.list', $customer))
    ->assertSessionHasNoErrors()
    ->assertSessionMissing('flash.error')
      ->assertSessionHas('flash.success', 'Repayment transaction created.');

    $this->assertDatabaseCount('credit_transactions', 1);
    $this->assertTrue(FzCustomer::first()->credit_transactions()->first()->is(CreditTransaction::first()));
    $this->assertTrue(SalesRep::first()->recorded_credit_transactions()->first()->is(CreditTransaction::first()));
    $this->assertTrue(CreditTransaction::first()->sales_rep->is(SalesRep::first()));
    $this->assertTrue(CreditTransaction::first()->customer->is(FzCustomer::first()));
    $this->assertEquals(20000, CreditTransaction::first()->amount);
    $this->assertContains(CreditTransaction::first()->payment_type, ['cash', 'bank']);
    $this->assertEquals('repayment', CreditTransaction::first()->trans_type);
    $this->assertEquals(now()->subDays(5)->startOfDay(), CreditTransaction::first()->trans_date);
  }

  /** @test */
  public function customer_credit_balance_will_increase_on_credit_repayment()
  {
    $this->withoutExceptionHandling();

    $customer = FzCustomer::factory()->create(['credit_limit' => 100000000000000, 'credit_balance' => 10000000000000]);
    $old_balance = $customer->credit_balance;
    $old_limit = $customer->credit_limit;

    FzStock::factory()->gallon()->create(['stock_quantity' => 150]);
    FzStock::factory()->count(3)->oil()->create(['stock_quantity' => 50]);

    $this->assertEquals(150, FzStock::gallon()->first()->stock_quantity);

    $this->actingAs($this->sales_rep, 'sales_rep')->post(route('purchaseorders.create', $customer), array_merge($this->data_to_create_customer_purchase_order(), ['fz_customer_id' => $customer->id, 'fz_product_type_id' => FzStock::oil()->first()->fz_product_type_id, 'fz_price_batch_id' => FzStock::oil()->first()->fz_price_batch_id, 'purchased_quantity' => 10, 'payment_type' => 'cash']));
    // ->dumpSession();

    $this->actingAs($this->sales_rep, 'sales_rep')->post(route('fzcustomer.credit_transactions.repayment', $customer), array_merge($this->data_to_create_credit_repayment(), ['amount' => 20000, 'recorded_by' => $this->sales_rep->id, 'trans_date' => now()->subDays(5)]))
      // ->dumpSession()
      ->assertSessionHas('flash.success', 'Repayment transaction created.');
    $customer->refresh();

    $this->assertDatabaseCount('credit_transactions', 1);
    $this->assertEquals($old_limit, $customer->credit_limit);
    $this->assertEquals($old_balance + 20000, $customer->credit_balance);
  }

  /** @test */
  public function sales_rep_cannot_create_credit_repayment_above_customer_debt()
  {
    $customer = FzCustomer::factory()->create();
    CreditTransaction::factory()->count(5)->bank()->purchase()->create(['fz_customer_id' => $customer->id, 'amount' => 3000]);

    $this->assertDatabaseCount('credit_transactions', 5);

    $this->actingAs($this->sales_rep, 'sales_rep')->post(route('fzcustomer.credit_transactions.repayment', $customer), array_merge($this->data_to_create_credit_repayment(), ['amount' => 20000, 'recorded_by' => $this->sales_rep->id, 'trans_date' => now()->subDays(5)]))
      // ->dumpSession()
      ->assertSessionHasErrors(['amount' => 'The customer\'s debt is not up to 20000']);

    $this->assertDatabaseCount('credit_transactions', 5);
  }

  /** @test */
  public function sales_rep_can_view_credit_repayment_list()
  {
    $customer = FzCustomer::factory()->create();
    CreditTransaction::factory()->count(5)->bank()->purchase()->create(['fz_customer_id' => $customer->id, 'amount' => 3000]);

    $rsp = $this->actingAs($this->sales_rep, 'sales_rep')->get(route('fzcustomer.credit_transactions.list', $customer))->assertOk();
    ray($page = $this->getResponseData($rsp));

    $this->assertEquals('FzCustomer::ManageCustomerCredit', $page->component);
    $this->assertArrayHasKey('errors', (array)$page->props);
    $this->assertArrayHasKey('credit_transactions', (array)$page->props);
    $this->assertArrayHasKey('credit_transactions_count', (array)$page->props);
    $this->assertCount(5, (array)$page->props->credit_transactions);
    $this->assertEquals(5, $page->props->credit_transactions_count);
  }

  /** @test */
  public function unverified_sales_rep_can_not_view_credit_repayments_list()
  {
    $customer = FzCustomer::factory()->create();

    $this->sales_rep->verified_at = null;
    $this->sales_rep->save();

    $this->actingAs($this->sales_rep, 'sales_rep')->get(route('fzcustomer.credit_transactions.list', $customer))->assertStatus(403);
  }

  /** @test */
  public function suspended_sales_rep_can_not_view_credit_repayments_list()
  {
    $customer = FzCustomer::factory()->create();

    $this->sales_rep->is_active = false;
    $this->sales_rep->save();

    $this->actingAs($this->sales_rep, 'sales_rep')->get(route('fzcustomer.credit_transactions.list', $customer))->assertStatus(403);
  }

}
