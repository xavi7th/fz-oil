<?php

namespace App\Modules\SuperAdmin\Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use App\Modules\SuperAdmin\Models\SwapDeal;
use Illuminate\Foundation\Testing\WithFaker;
use App\Modules\SuperAdmin\Models\SuperAdmin;
use App\Modules\AppUser\Models\ProductReceipt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\SuperAdmin\Models\ProductSaleRecord;

class SwapDealTest extends TestCase
{
  use RefreshDatabase;

  /** @test  */
  public function swap_deals_database_has_expected_columns()
  {
    $this->assertTrue(
      Schema::hasColumns('swap_deals', [
        'id', 'id_url', 'owner_details', 'description', 'swapped_in_record_id', 'receipt_url', 'imei',
        'serial_no', 'model_no', 'swap_value', 'selling_price', 'sold_at', 'swapped_with_id',
        'swapped_with_type', 'product_status_id', 'product_uuid'
      ]),
      1
    );
  }

  /** @test */
  public function a_swap_deal_can_retrieve_its_receipt()
  {
    factory(ProductSaleRecord::class)->states(['swap_deals_and_products'])->create();
    $swap_deal = SwapDeal::inRandomOrder()->first();

    $this->assertInstanceOf(ProductReceipt::class, $swap_deal->sale_record->sale_receipt);
    $this->assertEquals(1, $swap_deal->sale_record->sale_receipt->count());
    $this->assertDatabaseCount('product_receipts', 1);
  }

  /** @test */
  public function a_swap_deal_can_generate_its_details_page_url()
  {
    $user = factory(SuperAdmin::class)->create();
    $url = factory(SwapDeal::class)->states('in_stock')->create()->generateUrl($user);

    $this->actingAs($user, 'super_admin')->get($url)
      ->assertOk();
  }
}
