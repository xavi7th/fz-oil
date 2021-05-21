<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Factory;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use App\Modules\AppUser\Models\AppUser;
use Illuminate\Database\Eloquent\Model;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
| factory('App\User', 5)->create();
|
*/

$fakerNG = Factory::create('en_NG');

$factory->define(AppUser::class, function (Faker $faker) use ($fakerNG) {
  return [
    'first_name' => $name = $fakerNG->unique()->firstName,
    'last_name' => $faker->firstName,
    'email' => $name . '@' . strtolower(str_replace(" ", "", config('app.name'))) . '.com',
    'address' => $faker->address,
    'city' => $faker->city,
    'ig_handle' => '@' . $faker->unique()->userName,
    'password' => 'pass',
    'phone' => $faker->unique()->phoneNumber,
    'remember_token' => Str::random(10),
    'is_active' =>true,
  ];
});
