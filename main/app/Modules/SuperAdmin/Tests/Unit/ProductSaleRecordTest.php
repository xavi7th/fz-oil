<?php

namespace App\Modules\SuperAdmin\Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use App\Modules\SuperAdmin\Models\Product;
use App\Modules\SuperAdmin\Models\ProductAccessory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\SuperAdmin\Models\ProductSaleRecord;
use App\Modules\SuperAdmin\Models\SwapDeal;
use Illuminate\Database\Eloquent\Collection;

class ProductSaleRecordTest extends TestCase
{
  use RefreshDatabase;

  /** @test  */
  public function product_sale_records_database_has_expected_columns()
  {
    $this->assertTrue(
      Schema::hasColumns('product_sale_records', [
        'id', 'product_id', 'product_type', 'selling_price', 'online_rep_bonus', 'walk_in_rep_bonus', 'sales_channel_id', 'online_rep_id', 'sales_rep_id', 'sales_rep_type', 'sale_confirmed_by', 'sale_confirmer_type', 'is_swap_transaction'
      ]),
      1
    );
  }

  /** @test */
  public function a_sale_record_has_single_product()
  {
    $product_sale_record = factory(ProductSaleRecord::class)->states('single_product')->create();

    // Method 1:
    $this->assertInstanceOf(Product::class, $product_sale_record->products()->first());

    // Method 2:
    $this->assertEquals(1, $product_sale_record->products->count());

    $this->assertDatabaseCount('products', 1);
    $this->assertDatabaseCount('product_sale_records', 1);
  }

  /** @test */
  public function a_sale_record_has_multiple_products()
  {
    $product_sale_record = factory(ProductSaleRecord::class)->states('multiple_products')->create();

    $this->assertInstanceOf(Product::class, $product_sale_record->products()->inRandomOrder()->first());
    $this->assertGreaterThan(1, $product_sale_record->products->count());
    $this->assertDatabaseCount('product_sale_records', 1);
    $this->assertInstanceOf(Collection::class, $product_sale_record->products);
  }

  /** @test */
  public function a_sale_record_has_multiple_accessories()
  {
    $product_sale_record = factory(ProductSaleRecord::class)->states('multiple_accessories')->create();

    $this->assertDatabaseCount('product_sale_records', 1);
    $this->assertDatabaseCount('products', 0);
    $this->assertInstanceOf(ProductAccessory::class, $product_sale_record->product_accessories()->inRandomOrder()->first());
    $this->assertGreaterThan(1, $product_sale_record->product_accessories->count());
    $this->assertInstanceOf(Collection::class, $product_sale_record->products);
  }

  /** @test */
  public function a_sale_record_has_swap_deals_and_products()
  {
    $product_sale_record = factory(ProductSaleRecord::class)->states('swap_deals_and_products')->create();
    $product = factory(Product::class)->create();
    $product_sale_record->products()->syncWithoutDetaching([$product->id => ['selling_price' => 1000000, 'purchased_quantity' => 1]]);
    $product_sale_record->refresh();

    $this->assertDatabaseCount('product_sale_records', 1);
    $this->assertInstanceOf(SwapDeal::class, $product_sale_record->swap_deals()->inRandomOrder()->first());
    $this->assertInstanceOf(Product::class, $product_sale_record->products()->inRandomOrder()->first());
    $this->assertInstanceOf(Collection::class, $product_sale_record->products);
    $this->assertGreaterThan(1, $product_sale_record->products->count());
    $this->assertGreaterThan(1, $product_sale_record->swap_deals->count());
    $this->assertTrue($product_sale_record->products->contains($product));
  }

  /** @test */
  public function a_sale_record_can_generate_its_receipt_url()
  {
    /** @var ProductSaleRecord */
    $saleRecord = factory(ProductSaleRecord::class)->states('swap_deals_and_products', 'multiple_accessories', 'swap_transaction')->create();

    $this->assertDatabaseCount('product_receipts', 1);

    $url = $saleRecord->generateReceiptUrl();

    $this->get($url)
          ->assertOk()
          ->assertSee('Your ' .config('app.name') . ' Receipt')
          ->assertSee(config('app.email'));
  }
}
