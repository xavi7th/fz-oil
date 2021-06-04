<?php

namespace App\Modules\FzStockManagement\Database\Factories;

use App\Modules\FzStockManagement\Models\FzStock;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\FzStockManagement\Models\FzPriceBatch;
use App\Modules\FzStockManagement\Models\FzProductType;

class FzStockFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = FzStock::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'fz_product_type_id' => FzProductType::inRandomOrder()->first()->id,
      'fz_price_batch_id' => FzPriceBatch::factory()->create()->id,
      'stock_quantity' => $this->faker->randomDigitNotNull,
    ];
  }

  public function oil()
  {
    return $this->state(function (array $attributes) {
      return [
        'fz_product_type_id' => FzProductType::oil()->first()->id,
        'fz_price_batch_id' => FzPriceBatch::factory()->oil()->create()->id,
      ];
    });
  }

  public function gallon()
  {
    return $this->state(function (array $attributes) {
      return [
        'fz_product_type_id' => FzProductType::gallon()->first()->id,
        'fz_price_batch_id' => FzPriceBatch::factory()->gallon()->create()->id,
      ];
    });
  }
}
