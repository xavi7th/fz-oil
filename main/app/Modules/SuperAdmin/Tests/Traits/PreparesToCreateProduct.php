<?php

namespace App\Modules\SuperAdmin\Tests\Traits;

use Illuminate\Foundation\Testing\WithFaker;
use App\Modules\SuperAdmin\Models\ProductBatch;
use App\Modules\SuperAdmin\Database\Seeders\ProductStatusesTableSeeder;
use App\Modules\SuperAdmin\Database\Seeders\SalesChannelsTableSeeder;

/**
 * Does the necessary steps to enable the smooth gerenration of products
 */
trait PreparesToCreateProduct
{
  use WithFaker;

  public function setUp(): void
  {
    parent::setUp();

    $this->seed(ProductStatusesTableSeeder::class);
    $this->seed(SalesChannelsTableSeeder::class);
    factory(ProductBatch::class)->create(['batch_number' => 'LOCAL-SUPPLIER']);
    factory(ProductBatch::class)->create();
  }

}
