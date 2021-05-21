<?php

namespace App\Modules\OfficeExpense\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\OfficeExpense\Database\Factories\OfficeExpenseFactory;

class OfficeExpense extends Model
{
  use HasFactory;

  protected $fillable = [];

  const DASHBOARD_ROUTE_PREFIX = 'office-expenses';
  const ROUTE_NAME_PREFIX = 'officeexpense.';



  protected static function newFactory()
  {
    return OfficeExpenseFactory::new();
  }
}
