<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Modules\FzStaff\Models\ProductReceipt;
use App\Modules\SuperAdmin\Models\ProductSaleRecord;
use Faker\Generator as Faker;

$factory->define(ProductReceipt::class, function (Faker $faker) {
  return [
    'product_sale_record_id' => factory(ProductSaleRecord::class),
    'user_email' => $faker->email,
    'user_name' => $faker->name,
    'user_phone' => $faker->e164PhoneNumber,
    'user_address' => $faker->address,
    'user_city' => $faker->city
  ];
});
