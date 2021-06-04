<?php

namespace App\Modules\FzCustomer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\FzCustomer\Database\Factories\CreditTransactionFactory;
use App\Modules\SalesRep\Models\SalesRep;

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
    return self::cash()->notLodged()->sum('total_amount_paid');
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
