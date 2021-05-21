<?php
namespace App\Modules\SuperAdmin\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\SuperAdmin\Models\Product;

class ProductsTableSeeder extends Seeder
{

  /**
   * Auto generated seed file
   *
   * @return void
   */
  public function run()
  {
    factory(Product::class, 5)->create();
    factory(Product::class, 5)->states('local')->create();
    factory(Product::class, 3)->states('local', 'paid')->create();
    factory(Product::class, 5)->states('in_stock')->create();
    factory(Product::class, 5)->states('in_stock', 'local')->create();
    factory(Product::class, 2)->states('in_stock', 'local', 'paid')->create();
    factory(Product::class, 2)->states('sold', 'local', 'paid')->create();
    factory(Product::class, 15)->states('sold')->create();
    factory(Product::class, 10)->states('confirmed')->create();
    factory(Product::class, 10)->states('confirmedSwapTransactions')->create();
    // factory(Product::class, 20)->states('inStockWithExpenses')->create();
  }
}
