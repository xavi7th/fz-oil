<?php

namespace App\Modules\SuperAdmin\Database\State;

use App\Modules\FzStockManagement\Models\FzProductType;
use DB;


class EnsureProductTypesArePresentInDatabase
{
  public function __invoke()
  {
    if ($this->branches_present()) {
      return;
    }

    FzProductType::factory()->count(2)->create();
  }

  public function branches_present(): bool
  {
    return DB::table('fz_product_types')->count() > 0;
  }
}
