<?php

namespace App\Modules\SuperAdmin\Models;

use App\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\SuperAdmin\Models\ProductSaleRecord;
use App\Modules\SuperAdmin\Models\SalesRecordBankAccount;

/**
 * App\Modules\SuperAdmin\Models\CompanyBankAccount
 *
 * @property int $id
 * @property string $bank
 * @property string $account_name
 * @property string $account_number
 * @property string $account_type
 * @property string|null $img_url
 * @property string|null $account_description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|SalesRecordBankAccount[] $payment_records
 * @property-read int|null $payment_records_count
 * @property-read \Illuminate\Database\Eloquent\Collection|ProductSaleRecord[] $sales_records
 * @property-read int|null $sales_records_count
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyBankAccount cashTransactions()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyBankAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyBankAccount newQuery()
 * @method static \Illuminate\Database\Query\Builder|CompanyBankAccount onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyBankAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyBankAccount whereAccountDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyBankAccount whereAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyBankAccount whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyBankAccount whereAccountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyBankAccount whereBank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyBankAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyBankAccount whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyBankAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyBankAccount whereImgUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyBankAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|CompanyBankAccount withTrashed()
 * @method static \Illuminate\Database\Query\Builder|CompanyBankAccount withoutTrashed()
 * @mixin \Eloquent
 */
class CompanyBankAccount extends BaseModel
{

  use SoftDeletes;

  protected $fillable = [

  ];

  public function sales_records()
  {
    return $this->belongsToMany(ProductSaleRecord::class, $table = 'sales_record_bank_accounts')->using(SalesRecordBankAccount::class)
      ->as('payment_record')->withPivot('amount')->withTimestamps();
  }

  public function payment_records()
  {
    return $this->hasMany(SalesRecordBankAccount::class);
  }

  public function payment_records_sum(): float
  {
    return $this->payment_records()->sum('amount');
  }

  public function total_sales_records_amount()
  {
    return $this->sales_records()->sum('amount');
  }

  public function today_payment_records_sum()
  {
    return $this->sales_records()->today()->sum('amount');
  }

  static function cash_transaction_id(): int
  {
    return optional(self::where('bank', 'Cash')->first())->id ?? 0;
  }

  /**
   * Scope a query to only cash transactions
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeCash_transactions($query)
  {
    return $query->where('id', self::cash_transaction_id());
  }


  protected static function boot()
  {
    parent::boot();
  }
}
