<?php

namespace App\Modules\SuperAdmin\Tests\Traits;

use App\Modules\Admin\Models\Admin;
use App\Modules\FzStaff\Models\FzStaff;
use App\Modules\SalesRep\Models\SalesRep;
use App\Modules\WebAdmin\Models\WebAdmin;
use Illuminate\Foundation\Testing\WithFaker;
use App\Modules\Accountant\Models\Accountant;
use App\Modules\SuperAdmin\Models\SuperAdmin;
use App\Modules\StockKeeper\Models\StockKeeper;
use App\Modules\DispatchAdmin\Models\DispatchAdmin;
use App\Modules\QualityControl\Models\QualityControl;

/**
 * Does the necessary steps to enable the smooth gerenration of products
 */
trait ProvidesDifferentUserDataTypes
{
  use WithFaker;

  public function provideDifferentUserTypesWithoutAccountant()
  {
    return $this->provideDifferentUserTypes(['accountant', 'fzstaff']);
  }

  public function provideDifferentUserTypesWithoutDispatchAdmin()
  {
    return $this->provideDifferentUserTypes(['dispatchadmin', 'fzstaff']);
  }

  public function provideDifferentUserTypesWithoutSuperAdmin()
  {
    return $this->provideDifferentUserTypes(['superadmin', 'fzstaff']);
  }

  public function provideDifferentUserTypesWithoutSuperAdminAndAccountant()
  {
    return $this->provideDifferentUserTypes(['superadmin', 'accountant', 'fzstaff']);
  }

  public function provideDifferentUserTypesWithAllUsers()
  {
    return $this->provideDifferentUserTypes(['fzstaff']);
  }


  protected function provideDifferentUserTypes(array $typesToSkip = [])
  {
    /**
     * @key salesrep is a handle for the test to refer to that pasrticular dataset
     * @fn() is required because data providers are fired before the test class is initialised. The application and the IOC is bootstrapped when the calss is initialised ao factories and other stuffs like that fail and trigger not found errors
     * @factoryOffice branch is required because the data is fired and cached before the test is initialised. When the test is initialised the database is refreshed so we need to re fire the office location factory for each dataset otherwise there will be no location in the DB
     * @factoryUser is the main thin we are needing as we see grabbed from the method using destructuring
     */
    return collect([
      'salesrep' => [
        fn () => [factory(SalesRep::class)->create(['id' => 2])]
      ],
      'admin' => [
        fn () => [factory(Admin::class)->create(['id' => 2])],
      ],
      'accountant' => [
        fn () => [factory(Accountant::class)->create(['id' => 2])],
      ],
      'webadmin' => [
        fn () => [factory(WebAdmin::class)->create(['id' => 2])],
      ],
      'qualitycontrol' => [
        fn () => [factory(QualityControl::class)->create(['id' => 2])],
      ],
      'stockkeeper' => [
        fn () => [factory(StockKeeper::class)->create(['id' => 2])],
      ],
      'dispatchadmin' => [
        fn () => [factory(DispatchAdmin::class)->create(['id' => 2])],
      ],
      'superadmin' => [
        fn () => [factory(SuperAdmin::class)->create(['id' => 2])],
      ],
      'fzstaff' => [
        fn () => [factory(FzStaff::class)->create(['id' => 2])],
      ],
    ])->reject(fn ($val, $key) => in_array($key, $typesToSkip))->all();
  }
}
