<?php

namespace App\Modules\CompanyBankAccount\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Modules\CompanyBankAccount\Database\Factories\CompanyBankAccountFactory;

class CompanyBankAccount extends Model
{
  use HasFactory;

  protected $fillable = [];

  const DASHBOARD_ROUTE_PREFIX = 'company-bank-accounts';
  const ROUTE_NAME_PREFIX = 'companybankaccount.';


  protected static function newFactory()
  {
    return CompanyBankAccountFactory::new();
  }
}
