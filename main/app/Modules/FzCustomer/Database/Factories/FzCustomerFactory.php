<?php
namespace App\Modules\FzCustomer\Database\Factories;

use App\Modules\FzCustomer\Models\FzCustomer;
use Illuminate\Database\Eloquent\Factories\Factory;

class FzCustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FzCustomer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
      'full_name' => $this->faker->unique()->name,
      'email' => $this->faker->unique()->email,
      'phone' => $this->faker->unique()->phoneNumber,
      'gender' => $this->faker->randomElement(['male', 'female']),
      'address' => $this->faker->unique()->address,
      'credit_limit' => $this->faker->unique()->email,
      'is_flagged' => false,
      'is_active' => true,
    ];
  }

  public function suspended()
  {
    return $this->state(function (array $attributes) {
      return [
        'is_active' => false,
      ];
    });
  }

  public function flagged($reason)
  {
    return $this->state(function (array $attributes) use ($reason) {
      return [
        'is_flagged' => true,
        'flag_message' => $reason
      ];
    });
  }

  public function configure()
  {
    return $this->afterMaking(function (FzCustomer $user) {
      //SalesRep
    })->afterCreating(function (FzCustomer $user) {
      //
    });
    }

}
