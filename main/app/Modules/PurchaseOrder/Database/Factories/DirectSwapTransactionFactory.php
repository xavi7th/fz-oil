<?php

namespace App\Modules\PurchaseOrder\Database\Factories;

use App\Modules\SalesRep\Models\SalesRep;
use App\Modules\FzCustomer\Models\FzCustomer;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\PurchaseOrder\Models\DirectSwapTransaction;
use App\Modules\CompanyBankAccount\Models\CompanyBankAccount;
use App\Modules\FzStockManagement\Models\FzProductType;

class DirectSwapTransactionFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = DirectSwapTransaction::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'fz_product_type_id' => optional(FzProductType::inRandomOrder()->first())->id ?? FzProductType::factory()->create(),
      'fz_customer_id' => optional(FzCustomer::inRandomOrder()->first())->id ?? FzCustomer::factory()->create(),
      'sales_rep_id' => optional(SalesRep::inRandomOrder()->first())->id ?? SalesRep::factory()->create(),
      'company_bank_account_id' => optional(CompanyBankAccount::inRandomOrder()->first())->id ?? CompanyBankAccount::factory()->create(),
      'quantity' => $this->faker->randomDigitNotNull,
      'amount' => $this->faker->randomFloat(),
      'customer_paid_via' => $this->faker->randomElement(['cash', 'bank']),
    ];
  }

  public function cash()
  {
    return $this->state(function (array $attributes) {
      return [
        'customer_paid_via' => 'cash',
      ];
    });
  }

  public function bank()
  {
    return $this->state(function (array $attributes) {
      return [
        'customer_paid_via' => 'bank',
      ];
    });
  }
}
