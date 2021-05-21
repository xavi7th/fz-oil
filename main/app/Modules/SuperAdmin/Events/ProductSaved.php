<?php

namespace App\Modules\SuperAdmin\Events;

use App\Modules\SuperAdmin\Models\Product;
use Illuminate\Queue\SerializesModels;

class ProductSaved
{
  use SerializesModels;

  public $product;

  public function __construct(Product $product)
  {
    $this->product = $product;
  }
}
