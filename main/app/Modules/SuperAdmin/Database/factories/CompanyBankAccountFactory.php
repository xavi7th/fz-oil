<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Modules\SuperAdmin\Models\CompanyBankAccount;
use Faker\Generator as Faker;

$factory->define(CompanyBankAccount::class, function (Faker $faker) {
  return [
    'bank' => $faker->randomElement(['UBA', 'GTB', 'Zenith', 'Access', 'BankPHP', 'Sterling', 'FCMB']),
    'account_name' => $faker->name,
    'account_number' => $faker->bankAccountNumber,
    'account_type' => $faker->companySuffix,
    'account_description' => $faker->sentence,
    'img_url' => $faker->imageUrl()
  ];
});
