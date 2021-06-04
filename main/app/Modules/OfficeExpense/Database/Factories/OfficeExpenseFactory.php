<?php

namespace App\Modules\OfficeExpense\Database\Factories;

use App\Modules\OfficeExpense\Models\OfficeExpense;
use App\Modules\SalesRep\Models\SalesRep;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfficeExpenseFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = OfficeExpense::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'amount' => $this->faker->randomFloat(),
      'payment_type' => 'transfer',
      'description' => $this->faker->sentence,
      'expense_date' => $this->faker->dateTimeThisMonth,
      'sales_rep_id' => SalesRep::factory()->create()->id,
    ];
  }

  public function cash()
  {
    return $this->state(function (array $attributes) {
      return [
        'payment_type' => 'cash',
      ];
    });
  }
}
