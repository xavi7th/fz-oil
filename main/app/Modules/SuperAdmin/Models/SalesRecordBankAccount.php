<?php

namespace App\Modules\SuperAdmin\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Modules\SuperAdmin\Models\ProductSaleRecord;
use App\Modules\SuperAdmin\Models\CompanyBankAccount;

/**
 * App\Modules\SuperAdmin\Models\SalesRecordBankAccount
 *
 * @property int $id
 * @property int $product_sale_record_id
 * @property int $company_bank_account_id
 * @property float $amount
 * @property int $is_refund
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read CompanyBankAccount $company_bank_account
 * @property-read ProductSaleRecord $product_sale_record
 * @method static \Illuminate\Database\Eloquent\Builder|SalesRecordBankAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SalesRecordBankAccount newQuery()
 * @method static \Illuminate\Database\Query\Builder|SalesRecordBankAccount onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SalesRecordBankAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder|SalesRecordBankAccount thisMonth()
 * @method static \Illuminate\Database\Eloquent\Builder|SalesRecordBankAccount today()
 * @method static \Illuminate\Database\Eloquent\Builder|SalesRecordBankAccount whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SalesRecordBankAccount whereCompanyBankAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SalesRecordBankAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SalesRecordBankAccount whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SalesRecordBankAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SalesRecordBankAccount whereIsRefund($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SalesRecordBankAccount whereProductSaleRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SalesRecordBankAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|SalesRecordBankAccount withTrashed()
 * @method static \Illuminate\Database\Query\Builder|SalesRecordBankAccount withoutTrashed()
 * @mixin \Eloquent
 */
class SalesRecordBankAccount extends Pivot
{
  use SoftDeletes;

  protected $fillable = ['company_bank_account_id', 'amount', 'is_refund', 'product_sale_record_id'];
  protected $casts = [
  ];

  protected $table = "sales_record_bank_accounts";

  public function company_bank_account()
  {
    return $this->belongsTo(CompanyBankAccount::class);
  }

  public function product_sale_record()
  {
    return $this->belongsTo(ProductSaleRecord::class);
  }


  public function scopeToday($query)
  {
    return $query->whereDate('created_at', today());
  }

  public function scopeThisMonth($query)
  {
    return $query->whereMonth('created_at', today());
  }
}
