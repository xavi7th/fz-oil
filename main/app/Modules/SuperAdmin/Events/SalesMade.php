<?php

namespace App\Modules\SuperAdmin\Events;

use App\User;
use Illuminate\Queue\SerializesModels;
use App\Modules\SuperAdmin\Models\ProductSaleRecord;

class SalesMade
{
  use SerializesModels;

  public $sale_record;
  public $user;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct(ProductSaleRecord $sale_record, User $user)
  {
    $this->sale_record = $sale_record;
    $this->user = $user;
  }
}
