<?php

namespace App\Modules\SuperAdmin\Database\Factories;

use Faker\Generator;
use Illuminate\Support\Str;
use Faker\Factory as FakerFactory;
use App\Modules\SuperAdmin\Models\StaffRole;
use App\Modules\SuperAdmin\Models\SuperAdmin;
use Illuminate\Database\Eloquent\Factories\Factory;

class SuperAdminFactory extends Factory
{

  protected $model = SuperAdmin::class;
  private $fakerNG;

  public function setNGFaker()
  {
    $this->fakerNG = FakerFactory::create('en_NG');
    return $this;
  }

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    $this->setNGFaker();

    return [
      'full_name' => $name = $this->fakerNG->unique()->firstName,
      'email' => strtolower($name) . '@' . strtolower(str_replace(" ", "", config('app.name'))) . '.com',
      'user_name' => $this->faker->unique()->userName,
      'password' => $this->faker->password,
      'phone' => $this->faker->unique()->phoneNumber,
      'gender' => $this->faker->randomElement(['male', 'female']),
      'address' => $this->fakerNG->address,
      'id_url' => $this->faker->imageUrl(),
      'is_active' => true,
      'staff_role_id' => StaffRole::superAdminId(),
    ];
  }
}
