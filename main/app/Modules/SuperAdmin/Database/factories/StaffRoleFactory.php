<?php
namespace App\Modules\SuperAdmin\Database\Factories;

use App\Modules\SuperAdmin\Models\StaffRole;
use Illuminate\Database\Eloquent\Factories\Factory;

class StaffRoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StaffRole::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'role_name' => $this->faker->unique()->randomElement(['super_admin', 'supervisor', 'sales_rep', 'admin'])
        ];
    }
}
