<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Factory;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use App\Modules\SuperAdmin\Models\SuperAdmin;

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

$factory->define(SuperAdmin::class, function (Faker $faker) use ($fakerNG) {
  return [
    'full_name' => $name = $fakerNG->unique()->firstName,
    'email' => $name . '@' . strtolower(str_replace(" ", "", config('app.name'))) . '.com',
    'password' => 'pass',
    'remember_token' => Str::random(10),
  ];
});
