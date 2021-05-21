<?php

namespace App\Modules\FzStaff\Database\Factories;

use Illuminate\Support\Str;
use Faker\Factory as FakerFactory;
use App\Modules\FzStaff\Models\FzStaff;
use Illuminate\Database\Eloquent\Factories\Factory;

class FzStaffFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = FzStaff::class;
  private $fakerNG;

  public function __construct()
  {
    parent::__construct();

    $this->fakerNG = FakerFactory::create('en_NG');
  }

  public function definition()
  {
    return [
      'first_name' => $name = $this->fakerNG->unique()->firstName,
      'last_name' => $this->faker->firstName,
      'email' => strtolower($name) . '@' . strtolower(str_replace(" ", "", config('app.name'))) . '.com',
      'address' => $this->faker->address,
      'city' => $this->faker->city,
      'ig_handle' => '@' . $this->faker->unique()->userName,
      'password' => 'pass',
      'phone' => $this->faker->unique()->phoneNumber,
      'remember_token' => Str::random(10),
      'is_active' => true,
    ];
  }


  /**
   * Indicate that the user is suspended.
   *
   * @return \Illuminate\Database\Eloquent\Factories\Factory
   */
  public function suspended()
  {
    return $this->state(function (array $attributes) {
      return [
        'is_active' => false,
      ];
    });
  }

  /**
   * Configure the model factory.
   *
   * @return $this
   */
  public function configure()
  {
    return $this->afterMaking(function (FzStaff $user) {
      //
    })->afterCreating(function (FzStaff $user) {
      //
    });
  }
}
