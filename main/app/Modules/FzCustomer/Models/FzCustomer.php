<?php

namespace App\Modules\FzCustomer\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\PurchaseOrder\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\PurchaseOrder\Models\DirectSwapTransaction;
use App\Modules\FzCustomer\Database\Factories\FzCustomerFactory;

/**
 * App\Modules\FzCustomer\Models\FzCustomer
 *
 * @property int $id
 * @property string $full_name
 * @property string $email
 * @property string $phone
 * @property string $gender
 * @property string $address
 * @property float $credit_balance
 * @property float $credit_limit
 * @property bool $is_active
 * @property bool $is_flagged
 * @property string|null $flag_message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\FzCustomer\Models\CreditTransaction[] $credit_transactions
 * @property-read int|null $credit_transactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|DirectSwapTransaction[] $direct_swap_transactions
 * @property-read int|null $direct_swap_transactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|PurchaseOrder[] $purchase_orders
 * @property-read int|null $purchase_orders_count
 * @method static Builder|FzCustomer active()
 * @method static Builder|FzCustomer flagged()
 * @method static Builder|FzCustomer suspended()
 * @method static Builder|FzCustomer whereAddress($value)
 * @method static Builder|FzCustomer whereCreatedAt($value)
 * @method static Builder|FzCustomer whereCreditBalance($value)
 * @method static Builder|FzCustomer whereCreditLimit($value)
 * @method static Builder|FzCustomer whereDeletedAt($value)
 * @method static Builder|FzCustomer whereEmail($value)
 * @method static Builder|FzCustomer whereFlagMessage($value)
 * @method static Builder|FzCustomer whereFullName($value)
 * @method static Builder|FzCustomer whereGender($value)
 * @method static Builder|FzCustomer whereId($value)
 * @method static Builder|FzCustomer whereIsActive($value)
 * @method static Builder|FzCustomer whereIsFlagged($value)
 * @method static Builder|FzCustomer wherePhone($value)
 * @method static Builder|FzCustomer whereUpdatedAt($value)
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

  public function direct_swap_transactions()
  {
    return $this->hasMany(DirectSwapTransaction::class);
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
