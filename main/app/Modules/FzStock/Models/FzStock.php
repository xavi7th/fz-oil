<?php

namespace App\Modules\FzStock\Models;

use App\BaseModel;

class FzStock extends BaseModel
{

  protected $fillable = [];

  const DASHBOARD_ROUTE_PREFIX = 'fz-stock';
  const ROUTE_NAME_PREFIX = 'fzstock.';
}
