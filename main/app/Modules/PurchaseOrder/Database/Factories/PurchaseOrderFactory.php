<?php

namespace App\Modules\PurchaseOrder\Database\Factories;

use App\Modules\FzCustomer\Models\FzCustomer;
use App\Modules\FzStockManagement\Models\FzPriceBatch;
use App\Modules\FzStockManagement\Models\FzProductType;
use App\Modules\PurchaseOrder\Models\PurchaseOrder;
use App\Modules\SalesRep\Models\SalesRep;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseOrderFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = PurchaseOrder::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'fz_customer_id' => FzCustomer::factory()->create()->id,
      'fz_product_type_id' => optional(FzProductType::first())->id ?? FzProductType::factory()->create()->id,
      'fz_price_batch_id' => optional(FzPriceBatch::first())->id ?? FzPriceBatch::factory()->create()->id,
      'sales_rep_id' => optional(SalesRep::first())->id ?? SalesRep::factory()->create()->id,
      'purchased_quantity' => $this->faker->randomDigitNotNull,
      'is_swap_transaction' => false,
      'total_selling_price' => $this->faker->randomFloat,
      'total_amount_paid' => $this->faker->randomFloat,
      'payment_type' => $this->faker->randomElement(['cash', 'bank', 'credit'])
    ];
  }

  public function swap_deal()
  {
    return $this->state(function (array $attributes) {
      return [
        'is_swap_transaction' => true,
        'swap_product_type_id' => optional(FzProductType::first())->id ?? FzProductType::factory()->create()->id,
        'swap_quantity' => $this->faker->randomDigitNotNull,
        'swap_value' => $this->faker->randomFloat,
      ];
    });
  }

  public function cash()
  {
    return $this->state(function (array $attributes) {
      return [
        'payment_type' => 'cash'
      ];
    });
  }
}
