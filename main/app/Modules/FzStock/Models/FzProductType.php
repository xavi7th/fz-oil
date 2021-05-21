<?php

namespace App\Modules\FzStock\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\FzStock\Database\Factories\FzProductTypeFactory;

class FzProductType extends Model
{
    use HasFactory;

    protected $fillable = [];

  const DASHBOARD_ROUTE_PREFIX = 'product-types';
  const ROUTE_NAME_PREFIX = 'producttype.';



    protected static function newFactory()
    {
        return FzProductTypeFactory::new();
    }
}
