<?php

namespace App\Modules\FzCustomer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\FzCustomer\Database\Factories\CreditTransactionFactory;
use App\Modules\SalesRep\Models\SalesRep;

/**
 * App\Modules\FzCustomer\Models\CreditTransaction
 *
 * @property int $id
 * @property int $fz_customer_id
 * @property int $recorded_by
 * @property string $trans_type
 * @property float $amount
 * @property \Illuminate\Support\Carbon $trans_date
 * @property string $payment_type
 * @property int|null $company_bank_account_id
 * @property int $is_lodged
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Modules\FzCustomer\Models\FzCustomer $customer
 * @property-read mixed $total_cost_price
 * @property-read SalesRep $sales_rep
 * @method static Builder|CreditTransaction bank()
 * @method static Builder|CreditTransaction cash()
 * @method static Builder|CreditTransaction credit()
 * @method static \App\Modules\FzCustomer\Database\Factories\CreditTransactionFactory factory(...$parameters)
 * @method static Builder|CreditTransaction newModelQuery()
 * @method static Builder|CreditTransaction newQuery()
 * @method static Builder|CreditTransaction notLodged()
 * @method static Builder|CreditTransaction query()
 * @method static Builder|CreditTransaction repayment()
 * @method static Builder|CreditTransaction whereAmount($value)
 * @method static Builder|CreditTransaction whereCompanyBankAccountId($value)
 * @method static Builder|CreditTransaction whereCreatedAt($value)
 * @method static Builder|CreditTransaction whereFzCustomerId($value)
 * @method static Builder|CreditTransaction whereId($value)
 * @method static Builder|CreditTransaction whereIsLodged($value)
 * @method static Builder|CreditTransaction wherePaymentType($value)
 * @method static Builder|CreditTransaction whereRecordedBy($value)
 * @method static Builder|CreditTransaction whereTransDate($value)
 * @method static Builder|CreditTransaction whereTransType($value)
 * @method static Builder|CreditTransaction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CreditTransaction extends Model
{
  use HasFactory;

  protected $fillable = ['fz_customer_id', 'recorded_by', 'trans_type', 'amount', 'trans_date', 'payment_type', 'company_bank_account_id'];
  protected $casts = [
    'amount' => 'float',
    'trans_date' => 'date',
  ];

  public function customer()
  {
    return $this->belongsTo(FzCustomer::class, 'fz_customer_id');
  }

  public function sales_rep()
  {
    return $this->belongsTo(SalesRep::class, 'recorded_by');
  }

  static function cashInOffice(): float
  {
    return self::cash()->repayment()->notLodged()->sum('amount');
  }

  public function getTotalCostPriceAttribute()
  {
    return $this->quantity * $this->product_type->cost_price;
  }

  public function scopeNotLodged(Builder $query)
  {
    return $query->where('is_lodged', false);
  }

  public function scopeRepayment(Builder $query)
  {
    return $query->where('trans_type', 'repayment');
  }

  public function purchase(Builder $query)
  {
    return $query->where('trans_type', 'purchase');
  }

  public function scopeCash(Builder $query)
  {
    return $query->where('payment_type', 'cash');
  }

  public function scopeBank(Builder $query)
  {
    return $query->where('payment_type', 'bank');
  }

  public function scopeCredit(Builder $query)
  {
    return $query->where('payment_type', 'credit');
  }

  protected static function newFactory()
  {
    return CreditTransactionFactory::new();
  }
}
