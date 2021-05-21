<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Modules\SuperAdmin\Models\ProductStatus;
use Faker\Generator as Faker;

$factory->define(ProductStatus::class, function (Faker $faker) {
  return [
    'status' => strtolower($faker->unique()->randomElement(['Just arrived', 'Undergoing QA', 'Out for repairs', 'RTO (Damaged)', 'Back from repairs', 'In stock', 'With reseller', 'Sold', 'QA failed', 'sale confirmed', 'Sold by Reseller', 'Out for Delivery',]))
  ];
});
