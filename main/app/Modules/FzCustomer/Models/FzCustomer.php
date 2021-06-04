<?php

namespace App\Modules\FzCustomer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\FzCustomer\Database\Factories\FzCustomerFactory;
use App\Modules\PurchaseOrder\Models\PurchaseOrder;
use Carbon\Carbon;

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

  protected $fillable = ['full_name', 'email', 'phone', 'gender', 'address', 'credit_limit', 'credit_balance'];

  protected $casts = ['credit_limit' => 'float', 'credit_balance' => 'float', 'is_flagged' => 'bool', 'is_active' => 'bool',];

  const DASHBOARD_ROUTE_PREFIX = 'customers';
  const ROUTE_NAME_PREFIX = 'fzcustomer.';

  public function purchase_orders()
  {
    return $this->hasMany(PurchaseOrder::class);
  }

  public function credit_transactions()
  {
    return $this->hasMany(CreditTransaction::class);
  }

  public function createCreditPurchaseTransaction(float $amount, int $recorder_id, string $payment_type, int $bank_id = null): CreditTransaction
  {
    return $this->credit_transactions()->create([
      'recorded_by' => $recorder_id,
      'trans_type' => 'purchase',
      'amount' => $amount,
      'trans_date' => now(),
      'payment_type' => $payment_type,
      'company_bank_account_id' => $bank_id,
      'is_lodged' => $payment_type != 'cash'
    ]);
  }

  public function createCreditRepaymentTransaction(float $amount, int $recorder_id, $date, string $payment_type, int $bank_id = null): CreditTransaction
  {
    return $this->credit_transactions()->create([
      'recorded_by' => $recorder_id,
      'trans_type' => 'repayment',
      'amount' => $amount,
      'trans_date' => $date,
      'payment_type' => $payment_type,
      'company_bank_account_id' => $bank_id,
      'is_lodged' => $payment_type != 'cash'
    ]);
  }

  public function deductCreditBalance(float $amount): int
  {
    return $this->decrement('credit_balance', $amount);
  }

  public function addToCreditBalance(float $amount): int
  {
    return $this->increment('credit_balance', $amount);
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
