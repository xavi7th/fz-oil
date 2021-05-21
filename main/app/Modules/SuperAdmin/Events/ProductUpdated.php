<?php

namespace App\Modules\SuperAdmin\Events;

use App\Modules\SuperAdmin\Models\Product;
use Illuminate\Queue\SerializesModels;

class ProductUpdated
{
  use SerializesModels;

  public $product;

  public function __construct(Product $product)
  {
    $this->product = $product;
  }
}
