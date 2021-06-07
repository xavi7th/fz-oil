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
 * @property int $id
 * @property int $sales_rep_id
 * @property string $amount
 * @property string $payment_type
 * @property string $description
 * @property string $expense_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read SalesRep $sales_rep
 * @method static Builder|OfficeExpense cash()
 * @method static Builder|OfficeExpense transfer()
 * @method static Builder|OfficeExpense whereAmount($value)
 * @method static Builder|OfficeExpense whereCreatedAt($value)
 * @method static Builder|OfficeExpense whereDeletedAt($value)
 * @method static Builder|OfficeExpense whereDescription($value)
 * @method static Builder|OfficeExpense whereExpenseDate($value)
 * @method static Builder|OfficeExpense whereId($value)
 * @method static Builder|OfficeExpense wherePaymentType($value)
 * @method static Builder|OfficeExpense whereSalesRepId($value)
 * @method static Builder|OfficeExpense whereUpdatedAt($value)
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

  public function scopeToday(Builder $query)
  {
    return $query->whereDate('created_at', today());
  }

  protected static function newFactory()
  {
    return OfficeExpenseFactory::new();
  }
}
