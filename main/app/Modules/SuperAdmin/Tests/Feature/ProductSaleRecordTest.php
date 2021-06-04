<?php

namespace App\Modules\SuperAdmin\Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\SuperAdmin\Tests\Traits\PreparesToCreateProduct;

class ProductSaleRecordTest extends TestCase
{
  // use RefreshDatabase, PreparesToCreateProduct;

  // /** @test */
  // public function super_admin_view_sale_record_details()
  // {
  //   Notification::fake();

  //   $this->prepareConfirmedSaleRecord();

  //   $prodSaleRec = ProductSaleRecord::first();

  //   $this->assertCount(1, ProductSaleRecord::all());
  //   $this->assertCount(1, FzStaff::all());
  //   $this->assertTrue($prodSaleRec->buyer->is(FzStaff::first()));

  //   $rsp = $this->actingAs(factory(SuperAdmin::class)->create(), 'super_admin')->get(route('generic.product_sales_records.show', $prodSaleRec))
  //     // ->dumpSession()
  //     ->assertSessionHasNoErrors()
  //     ->assertSessionMissing('flash.error');

  //   $page = $this->getResponseData($rsp);

  //   $this->assertEquals('SuperAdmin,Products/SalesRecordDetails', $page->component);
  //   $this->assertNull($page->props->errors);
  //   $this->assertArrayHasKey('sale_record', (array)$page->props);
  //   $this->assertArrayHasKey('product_accessories', (array)$page->props->sale_record);
  //   $this->assertArrayHasKey('sales_rep', (array)$page->props->sale_record);
  //   $this->assertArrayHasKey('online_rep', (array)$page->props->sale_record);
  //   $this->assertArrayHasKey('sale_confirmer', (array)$page->props->sale_record);
  //   $this->assertArrayHasKey('sales_channel', (array)$page->props->sale_record);
  //   $this->assertArrayHasKey('bank_account_payments', (array)$page->props->sale_record);
  //   $this->assertArrayHasKey('order_ref', (array)$page->props->sale_record);
  //   $this->assertNotNull($page->props->sale_record->sales_rep);
  //   $this->assertNotNull($page->props->sale_record->buyer);
  //   // $this->assertNotCount(0, (array)$page->props->returns_history);
  // }

}
