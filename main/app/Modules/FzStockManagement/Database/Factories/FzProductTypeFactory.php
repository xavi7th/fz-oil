<?php

namespace App\Modules\FzStockManagement\Database\Factories;

use App\Modules\FzStockManagement\Models\FzProductType;
use Illuminate\Database\Eloquent\Factories\Factory;

class FzProductTypeFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = FzProductType::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'product_type' => $this->faker->unique()->randomElement(['oil', 'gallon']),
      'swap_value' => $this->faker->randomFloat(2, 1000, 5000),
    ];
  }

  public function oil()
  {
    return $this->state(function (array $attributes) {
      return [
        'product_type' => optional(FzProductType::oil()->first())->product_type ?? 'oil',
        'swap_value' => 0,
      ];
    });
  }

  public function gallon()
  {
    return $this->state(function (array $attributes) {
      return [
        'product_type' => optional(FzProductType::gallon()->first())->product_type ?? 'gallon',
        'swap_value' => 0,
      ];
    });
  }
}
