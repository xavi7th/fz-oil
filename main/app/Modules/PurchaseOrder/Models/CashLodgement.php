<?php

namespace App\Modules\PurchaseOrder\Models;

use App\Modules\CompanyBankAccount\Models\CompanyBankAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\PurchaseOrder\Database\Factories\CashLodgementFactory;
use App\Modules\SalesRep\Models\SalesRep;

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
