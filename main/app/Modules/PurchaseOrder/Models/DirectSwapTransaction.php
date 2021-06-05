<?php

namespace App\Modules\PurchaseOrder\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\SalesRep\Models\SalesRep;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\FzCustomer\Models\FzCustomer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\FzStockManagement\Models\FzProductType;
use App\Modules\CompanyBankAccount\Models\CompanyBankAccount;
use App\Modules\PurchaseOrder\Database\Factories\DirectSwapTransactionFactory;

class DirectSwapTransaction extends Model
{
  use HasFactory;

  protected $fillable = ['fz_product_type_id', 'fz_customer_id', 'sales_rep_id', 'company_bank_account_id', 'quantity', 'amount', 'customer_paid_via',];
  protected $casts = [
    'fz_product_type_id' => 'int',
    'fz_customer_id' => 'int',
    'sales_rep_id' => 'int',
    'company_bank_account_id' => 'int',
    'quantity' => 'int',
    'amount' => 'float',
  ];

  public function fz_product_type()
  {
    return $this->belongsTo(FzProductType::class);
  }

  public function sales_rep()
  {
    return $this->belongsTo(SalesRep::class);
  }

  public function customer()
  {
    return $this->belongsTo(FzCustomer::class, 'fz_customer_id');
  }

  public function bank()
  {
    return $this->belongsTo(CompanyBankAccount::class, 'company_bank_id');
  }

  public function scopeCash(Builder $query)
  {
    return $query->where('customer_paid_via', 'cash');
  }

  public function scopeBank(Builder $query)
  {
    return $query->where('customer_paid_via', 'bank');
  }

  protected static function newFactory()
  {
    return DirectSwapTransactionFactory::new();
  }
}
