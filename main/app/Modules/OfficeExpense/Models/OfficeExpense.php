<?php

namespace App\Modules\OfficeExpense\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\OfficeExpense\Database\Factories\OfficeExpenseFactory;
use App\Modules\SalesRep\Models\SalesRep;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Modules\OfficeExpense\Models\OfficeExpense
 *
 * @method static \App\Modules\OfficeExpense\Database\Factories\OfficeExpenseFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeExpense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeExpense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfficeExpense query()
 * @mixin \Eloquent
 */
class OfficeExpense extends Model
{
  use HasFactory;

  protected $fillable = ['amount', 'payment_type', 'description', 'expense_date', 'sales_rep_id'];

  const DASHBOARD_ROUTE_PREFIX = 'office-expenses';
  const ROUTE_NAME_PREFIX = 'officeexpense.';

  public function sales_rep()
  {
    return $this->belongsTo(SalesRep::class);
  }

  public function scopeCash(Builder $query)
  {
    return $query->where('payment_type', 'cash');
  }

  public function scopeTransfer(Builder $query)
  {
    return $query->where('payment_type', 'transfer');
  }

  protected static function newFactory()
  {
    return OfficeExpenseFactory::new();
  }
}
