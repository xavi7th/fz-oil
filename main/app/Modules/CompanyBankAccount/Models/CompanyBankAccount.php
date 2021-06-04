<?php

namespace App\Modules\CompanyBankAccount\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\CompanyBankAccount\Database\Factories\CompanyBankAccountFactory;
use App\Modules\PurchaseOrder\Models\CashLodgement;

/**
 * App\Modules\CompanyBankAccount\Models\CompanyBankAccount
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
 * @property string|null $deleted_at
 * @method static \App\Modules\CompanyBankAccount\Database\Factories\CompanyBankAccountFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyBankAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyBankAccount newQuery()
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
 * @mixin \Eloquent
 */
class CompanyBankAccount extends Model
{
  use HasFactory;

  protected $fillable = ['account_name','acount_number','bank_name',];

  const DASHBOARD_ROUTE_PREFIX = 'company-bank-accounts';
  const ROUTE_NAME_PREFIX = 'companybankaccount.';

  public function cash_lodgements()
  {
    return $this->hasMany(CashLodgement::class);
  }


  protected static function newFactory()
  {
    return CompanyBankAccountFactory::new();
  }
}
