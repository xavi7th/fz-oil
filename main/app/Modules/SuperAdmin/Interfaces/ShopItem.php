<?php

namespace App\Modules\SuperAdmin\Interfaces;

use App\User;

interface ShopItem
{
  public function sale_record();
  public function sale_receipt();
}
