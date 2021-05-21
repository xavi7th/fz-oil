<?php

namespace App\Modules\FzStock\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\FzStock\Database\Factories\FzProductBatchFactory;

class FzProductBatch extends Model
{
    use HasFactory;

    protected $fillable = [];

  const DASHBOARD_ROUTE_PREFIX = 'product-batches';
  const ROUTE_NAME_PREFIX = 'productbatch.';



    protected static function newFactory()
    {
        return FzProductBatchFactory::new();
    }
}
