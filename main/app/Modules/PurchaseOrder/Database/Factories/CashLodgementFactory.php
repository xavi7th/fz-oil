<?php

namespace App\Modules\PurchaseOrder\Database\Factories;

use App\Modules\PurchaseOrder\Models\CashLodgement;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\CompanyBankAccount\Models\CompanyBankAccount;
use App\Modules\SalesRep\Models\SalesRep;

class CashLodgementFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = CashLodgement::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'company_bank_account_id' => CompanyBankAccount::factory()->create()->id,
      'amount' => $this->faker->randomFloat(),
      'lodgement_date' => $this->faker->dateTimeThisMonth,
      'teller_url' => $this->faker->imageUrl(),
      'sales_rep_id' => SalesRep::factory()->create()->id,
    ];
  }
}
