<?php

namespace App\Modules\SuperAdmin\Database\Factories;

use Faker\Generator;
use Illuminate\Support\Str;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class SuperAdminFactory extends Factory
{

  protected $model = SuperAdmin::class;
  private $fakerNG;

  public function __construct()
  {
    parent::__construct();

    $this->fakerNG = FakerFactory::create('en_NG');
  }

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'full_name' => $name = $this->fakerNG->unique()->firstName,
      'email' => strtolower($name) . '@' . strtolower(str_replace(" ", "", config('app.name'))) . '.com',
      'password' => 'pass',
      'remember_token' => Str::random(10),
    ];
  }
}
