<?php

namespace App\Modules\SuperAdmin\Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\SuperAdmin\Tests\Traits\PreparesToCreateProduct;

class ProductSaleRecordTest extends TestCase
{
  use RefreshDatabase, PreparesToCreateProduct;

  /** @test */
  public function super_admin_view_sale_record_details()
  {
    Notification::fake();

    $this->prepareConfirmedSaleRecord();

    $prodSaleRec = ProductSaleRecord::first();

    $this->assertCount(1, ProductSaleRecord::all());
    $this->assertCount(1, AppUser::all());
    $this->assertTrue($prodSaleRec->buyer->is(AppUser::first()));

    $rsp = $this->actingAs(factory(SuperAdmin::class)->create(), 'super_admin')->get(route('generic.product_sales_records.show', $prodSaleRec))
      // ->dumpSession()
      ->assertSessionHasNoErrors()
      ->assertSessionMissing('flash.error');

    $page = $this->getResponseData($rsp);

    $this->assertEquals('SuperAdmin,Products/SalesRecordDetails', $page->component);
    $this->assertNull($page->props->errors);
    $this->assertArrayHasKey('sale_record', (array)$page->props);
    $this->assertArrayHasKey('product_accessories', (array)$page->props->sale_record);
    $this->assertArrayHasKey('sales_rep', (array)$page->props->sale_record);
    $this->assertArrayHasKey('online_rep', (array)$page->props->sale_record);
    $this->assertArrayHasKey('sale_confirmer', (array)$page->props->sale_record);
    $this->assertArrayHasKey('sales_channel', (array)$page->props->sale_record);
    $this->assertArrayHasKey('bank_account_payments', (array)$page->props->sale_record);
    $this->assertArrayHasKey('order_ref', (array)$page->props->sale_record);
    $this->assertNotNull($page->props->sale_record->sales_rep);
    $this->assertNotNull($page->props->sale_record->buyer);
    // $this->assertNotCount(0, (array)$page->props->returns_history);
  }

  /** @test */
  public function accountant_can_view_sale_records_for_a_date()
  {
    $this->withoutExceptionHandling();

    factory(ProductSaleRecord::class)->states('multiple_accessories', 'swap_deals_and_products')->create(['created_at' => now()]);

    $rsp = $this->actingAs(factory(Accountant::class)->create(), 'accountant')->get(route('accountant.multiaccess.product_sales_records.daily', now()->toDateString()))
      // ->dumpSession()
      ->assertSessionHasNoErrors()
      ->assertSessionMissing('flash.error');

    $page = $this->getResponseData($rsp);

    $this->assertEquals('SuperAdmin,Products/SalesRecords', $page->component);
    $this->assertNull($page->props->errors);
    $this->assertArrayHasKey('date', (array)$page->props);
    $this->assertArrayHasKey('companyAccounts', (array)$page->props);
    $this->assertArrayHasKey('salesRecords', (array)$page->props);
  }

  /** @test */
  public function sale_record_payment_can_be_confirmed()
  {
    Notification::fake();
    $this->seed(CompanyBankAccountsTableSeeder::class);

    $this->prepareSaleRecord();

    $this->assertDatabaseCount('product_receipts', 1);
    $this->assertDatabaseCount('product_sale_records', 1);
    $this->assertDatabaseCount('sales_record_bank_accounts', 0);

    $productSaleRecord = ProductSaleRecord::first();

    $user = factory(Accountant::class)->create();
    $data = ['payment_records' => [['company_bank_id' =>1, 'amount' => ($productSaleRecord->total_amount_paid/3)], ['company_bank_id' => 2, 'amount'=> ($productSaleRecord->total_amount_paid/3)], ['company_bank_id' => 1, 'amount'=> ($productSaleRecord->total_amount_paid/3)]]];

    $this->actingAs($user, 'accountant')->post(route('accountant.product_sales_records.confirm_sale', $productSaleRecord), $data)
          ->assertRedirect()
          ->assertSessionHas('flash.success', "Sale record's payment has been marked as confirmed by you and the customer has been sent their receipt");

    $product = $productSaleRecord->products()->inRandomOrder()->first();

    $this->assertEquals($product->product_status_id, ProductStatus::saleConfirmedId());
    $this->assertNotNull($product->sold_at);
    $this->assertDatabaseCount('sales_record_bank_accounts', 3);
    $this->assertCount(3, $productSaleRecord->bank_account_payments);
    $this->assertEquals(round($productSaleRecord->total_amount_paid, 2), round($productSaleRecord->total_bank_payments_amount(), 2));


    Notification::assertSentTo($productSaleRecord->buyer, ProductReceiptNotification::class);
    Notification::assertNotSentTo(new AnonymousNotifiable, ErrorAlertNotification::class);
  }

}
