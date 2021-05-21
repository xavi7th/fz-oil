<?php

namespace App\Modules\SuperAdmin\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Modules\SuperAdmin\Models\SuperAdmin;
use App\Modules\SuperAdmin\Models\OfficeExpense;
use App\Modules\SuperAdmin\Database\Seeders\ProductsTableSeeder;
use App\Modules\SuperAdmin\Database\Seeders\ProductStatusesTableSeeder;
use App\Modules\SuperAdmin\Database\Seeders\ProductCategoriesTableSeeder;
use App\Modules\SuperAdmin\Database\Seeders\CompanyBankAccountsTableSeeder;

class SuperAdminDatabaseSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Model::unguard();

    SuperAdmin::create([
      'full_name' => 'SysDef SuperAdmin',
      'email' => 'biggie@' . strtolower(str_replace(" ", "", config('app.name'))) . '.website',
      'password' => 'theboss',
    ]);

    factory(SuperAdmin::class)->create(['full_name' => 'Romzy',
      'email' => 'the-boss@theelects.com',
      'password' => 'qaws@16#'
    ]);

    app()->environment('local') && factory(OfficeExpense::class, 1)->create();
    app()->environment('local') && factory(OfficeExpense::class, 1)->create();
    $this->call(ProductStatusesTableSeeder::class);
    $this->call(ProductCategoriesTableSeeder::class);
    app()->environment('local') && $this->call(CompanyBankAccountsTableSeeder::class);
    // $this->call(ProductPricesTableSeeder::class);
    app()->environment('local') && $this->call(ProductsTableSeeder::class);
    $this->call(ProductSaleRecordsTableSeeder::class);
  }
}
