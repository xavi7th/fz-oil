<?php

namespace App\Modules\FzCustomer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\FzCustomer\Database\Factories\FzCustomerFactory;
use App\Modules\PurchaseOrder\Models\PurchaseOrder;

/**
 * App\Modules\FzCustomer\Models\FzCustomer
 *
 * @method static \App\Modules\FzCustomer\Database\Factories\FzCustomerFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|FzCustomer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FzCustomer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FzCustomer query()
 * @mixin \Eloquent
 */
class FzCustomer extends Model
{
  use HasFactory;

  protected $fillable = ['full_name', 'email', 'phone', 'gender', 'address', 'credit_limit'];

  protected $casts = ['credit_limit' => 'float', 'is_flagged' => 'bool', 'is_active' => 'bool',];

  const DASHBOARD_ROUTE_PREFIX = 'customers';
  const ROUTE_NAME_PREFIX = 'fzcustomer.';

  public function purchase_orders()
  {
    return $this->hasMany(PurchaseOrder::class);
  }

  public function deductCreditBalance(float $amount): int
  {
    return $this->decrement('credit_limit', $amount);
  }

  public function addToCreditBalance(float $amount): int
  {
    return $this->increment('credit_limit', $amount);
  }

  public function scopeActive(Builder $query)
  {
    return $query->where('is_active', true);
  }

  public function scopeSuspended(Builder $query)
  {
    return $query->where('is_active', false);
  }

  public function scopeFlagged(Builder $query)
  {
    return $query->where('is_flagged', true);
  }

  protected static function newFactory()
  {
    return FzCustomerFactory::new();
  }
}
