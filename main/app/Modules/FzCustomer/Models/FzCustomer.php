<?php

namespace App\Modules\FzCustomer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\FzCustomer\Database\Factories\FzCustomerFactory;

class FzCustomer extends Model
{
  use HasFactory;

  protected $fillable = [];

  const DASHBOARD_ROUTE_PREFIX = 'customers';
  const ROUTE_NAME_PREFIX = 'customer.';



  protected static function newFactory()
  {
    return FzCustomerFactory::new();
  }
}
