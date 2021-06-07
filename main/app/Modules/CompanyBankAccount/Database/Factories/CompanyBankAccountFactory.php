<?php

namespace App\Modules\CompanyBankAccount\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\CompanyBankAccount\Models\CompanyBankAccount;

class CompanyBankAccountFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = CompanyBankAccount::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'account_name' => $this->faker->name,
      'account_number' => $this->faker->bankAccountNumber,
      'bank_name' => $this->faker->company,
      'is_active' => true
    ];
  }

  public function suspended()
  {
    return $this->state(function (array $attributes) {
      return [
        'is_active' => false,
      ];
    });
  }
}
