<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use App\Modules\SuperAdmin\Models\ProductCategory;

$factory->define(Product::class, function (Faker $faker) {
  return [
    'product_category_id' => rescue(fn () => ProductCategory::inRandomOrder()->first()->id, fn() => factory(ProductCategory::class), false),

  ];
});
