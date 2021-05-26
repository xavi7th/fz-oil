<?php

namespace App\Modules\SuperAdmin\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Modules\SuperAdmin\Models\SuperAdmin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\SuperAdmin\Database\Seeders\StaffRoleTableSeeder;

class SuperAdminTest extends TestCase
{
  use RefreshDatabase;

  public function setUp(): void
  {
    parent::setUp();

    $this->seed(StaffRoleTableSeeder::class);
  }

  /** @test  */
  public function super_admin_can_login()
  {
    $this->withoutExceptionHandling();

    $super_admin = SuperAdmin::factory()->create(['password' => 'pass']);

    $this->post(route('auth.login'), ['user_name' => $super_admin->user_name, 'password' => 'pass', 'remember' => true])
      ->assertRedirect(route('superadmin.dashboard'));
  }
}
