<?php

namespace App\Modules\FzCustomer\Database\Factories;

use App\Modules\CompanyBankAccount\Models\CompanyBankAccount;
use App\Modules\SalesRep\Models\SalesRep;
use App\Modules\FzCustomer\Models\FzCustomer;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\FzCustomer\Models\CreditTransaction;

class CreditTransactionFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = CreditTransaction::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'fz_customer_id' => FzCustomer::factory()->create(),
      'recorded_by' => SalesRep::factory()->create(),
      'trans_type' => $this->faker->randomElement(['repayment', 'purchase', 'purchase', 'purchase']),
      'amount' => $this->faker->randomFloat(),
      'trans_date' => $this->faker->dateTimeThisYear,
      'payment_type' => $this->faker->randomElement(['cash', 'bank']),
      'company_bank_account_id' => CompanyBankAccount::factory()->create(),
    ];
  }

  public function repayment()
  {
    return $this->state(function (array $attributes) {
      return [
        'trans_type' => 'repayment',
      ];
    });
  }

  public function purchase()
  {
    return $this->state(function (array $attributes) {
      return [
        'trans_type' => 'purchase',
      ];
    });
  }

  public function cash()
  {
    return $this->state(function (array $attributes) {
      return [
        'payment_type' => 'cash',
      ];
    });
  }

  public function bank()
  {
    return $this->state(function (array $attributes) {
      return [
        'payment_type' => 'bank',
      ];
    });
  }
}
