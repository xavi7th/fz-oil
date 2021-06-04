<?php

namespace App\Modules\FzStockManagement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\FzStockManagement\Models\FzPriceBatch;
use App\Modules\FzStockManagement\Models\FzProductType;

class FzPriceBatchFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = FzPriceBatch::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'fz_product_type_id' => optional(FzProductType::first())->id ?? FzProductType::factory()->create()->id,
      'cost_price' => $cp = $this->faker->randomFloat(2, $this->faker->randomElement([mt_rand(100, 20000) * .01 * 1000, mt_rand(100, 20000) * .01 * 1200, mt_rand(100, 20000) * .01 * 1500])),
      'selling_price' => $this->faker->randomFloat(2, $cp + 100, $cp * 2),
    ];
  }

  public function oil()
  {
    return $this->state(function (array $attributes) {
      return [
        'fz_product_type_id' => FzProductType::oil()->first()->id,
      ];
    });
  }

  public function gallon()
  {
    return $this->state(function (array $attributes) {
      return [
        'fz_product_type_id' => FzProductType::gallon()->first()->id,
        'selling_price' => 0,
      ];
    });
  }
}
