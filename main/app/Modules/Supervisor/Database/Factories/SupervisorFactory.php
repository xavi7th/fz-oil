<?php
namespace App\Modules\Supervisor\Database\Factories;

use App\Modules\SuperAdmin\Models\StaffRole;
use App\Modules\Supervisor\Models\Supervisor;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupervisorFactory extends Factory
{
    /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = Supervisor::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'email' => $this->faker->unique()->email,
      'user_name' => $this->faker->unique()->userName,
      'password' => $this->faker->password,
      'full_name' => $this->faker->unique()->name,
      'phone' => $this->faker->unique()->phoneNumber,
      'gender' => $this->faker->randomElement(['male', 'female']),
      'address' => $this->faker->address,
      'id_url' => $this->faker->imageUrl(),
      'is_active' => false,
      'staff_role_id' => StaffRole::supervisorId(),
    ];
  }

  public function active()
  {
    return $this->state(function (array $attributes) {
      return [
        'is_active' => true,
      ];
    });
  }

  public function configure()
  {
    return $this->afterMaking(function (Supervisor $user) {
      //SalesRep
    })->afterCreating(function (Supervisor $user) {
      //
    });
  }
}
