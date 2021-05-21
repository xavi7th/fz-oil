<?php

namespace App\Modules\SuperAdmin\Events;

use Illuminate\Queue\SerializesModels;
use App\Modules\SuperAdmin\Models\ProductSaleRecord;

class ProductSaleRecordConfirmed
{
  use SerializesModels;

  /**
   * The sale record object
   *
   * @var ProductSaleRecord
   */
  public $sale_record;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct(ProductSaleRecord $sale_record)
  {
    $this->sale_record = $sale_record;
  }
}
