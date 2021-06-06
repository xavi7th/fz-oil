<?php

namespace App\Modules\PurchaseOrder\Models;

use App\Modules\CompanyBankAccount\Models\CompanyBankAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\PurchaseOrder\Database\Factories\CashLodgementFactory;
use App\Modules\SalesRep\Models\SalesRep;

/**
 * App\Modules\PurchaseOrder\Models\CashLodgement
 *
 * @property int $id
 * @property int $sales_rep_id
 * @property int $company_bank_account_id
 * @property string $amount
 * @property string $lodgement_date
 * @property string $teller_url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read CompanyBankAccount $bank
 * @property-read SalesRep $sales_rep
 * @method static \App\Modules\PurchaseOrder\Database\Factories\CashLodgementFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|CashLodgement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashLodgement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashLodgement query()
 * @method static \Illuminate\Database\Eloquent\Builder|CashLodgement whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashLodgement whereCompanyBankAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashLodgement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashLodgement whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashLodgement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashLodgement whereLodgementDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashLodgement whereSalesRepId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashLodgement whereTellerUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashLodgement whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CashLodgement extends Model
{
  use HasFactory;

  protected $fillable = ['company_bank_account_id', 'amount', 'lodgement_date', 'teller_url', 'sales_rep_id'];

  public function bank()
  {
    return $this->belongsTo(CompanyBankAccount::class, 'company_bank_account_id');
  }

  public function sales_rep()
  {
    return $this->belongsTo(SalesRep::class);
  }

  protected static function newFactory()
  {
    return CashLodgementFactory::new();
  }
}
