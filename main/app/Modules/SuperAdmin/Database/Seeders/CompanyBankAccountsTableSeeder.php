<?php
namespace App\Modules\SuperAdmin\Database\Seeders;

use App\Modules\SuperAdmin\Models\CompanyBankAccount;
use Illuminate\Database\Seeder;

class CompanyBankAccountsTableSeeder extends Seeder
{

  /**
   * Auto generated seed file
   *
   * @return void
   */
  public function run()
  {
    !app()->environment('production') && factory(CompanyBankAccount::class, 2)->create();
  }
}
