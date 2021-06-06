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

/**
 * App\Modules\PurchaseOrder\Models\DirectSwapTransaction
 *
 * @property int $id
 * @property int $fz_customer_id
 * @property int $sales_rep_id
 * @property int $fz_product_type_id
 * @property int|null $company_bank_account_id
 * @property int $quantity
 * @property float $amount
 * @property string $customer_paid_via
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read CompanyBankAccount $bank
 * @property-read FzCustomer $customer
 * @property-read FzProductType $fz_product_type
 * @property-read SalesRep $sales_rep
 * @method static Builder|DirectSwapTransaction bank()
 * @method static Builder|DirectSwapTransaction cash()
 * @method static \App\Modules\PurchaseOrder\Database\Factories\DirectSwapTransactionFactory factory(...$parameters)
 * @method static Builder|DirectSwapTransaction newModelQuery()
 * @method static Builder|DirectSwapTransaction newQuery()
 * @method static Builder|DirectSwapTransaction query()
 * @method static Builder|DirectSwapTransaction whereAmount($value)
 * @method static Builder|DirectSwapTransaction whereCompanyBankAccountId($value)
 * @method static Builder|DirectSwapTransaction whereCreatedAt($value)
 * @method static Builder|DirectSwapTransaction whereCustomerPaidVia($value)
 * @method static Builder|DirectSwapTransaction whereDeletedAt($value)
 * @method static Builder|DirectSwapTransaction whereFzCustomerId($value)
 * @method static Builder|DirectSwapTransaction whereFzProductTypeId($value)
 * @method static Builder|DirectSwapTransaction whereId($value)
 * @method static Builder|DirectSwapTransaction whereQuantity($value)
 * @method static Builder|DirectSwapTransaction whereSalesRepId($value)
 * @method static Builder|DirectSwapTransaction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
