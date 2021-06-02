<?php

namespace App\Modules\SuperAdmin\Tests\Feature;

use Tests\TestCase;
use App\Modules\SalesRep\Models\SalesRep;
use Illuminate\Foundation\Testing\WithFaker;
use App\Modules\Supervisor\Models\Supervisor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Modules\SuperAdmin\Database\Seeders\StaffRoleTableSeeder;

class SalesRepTest extends TestCase
{
  use RefreshDatabase, WithFaker;

  private $sales_rep;

  public function setUp(): void
  {
    parent::setUp();

    $this->seed(StaffRoleTableSeeder::class);

    $this->sales_rep = SalesRep::factory()->verified()->active()->create(['password' => 'pass']);
  }

  /** @test  */
  public function sales_rep_can_login()
  {
    $this->withoutExceptionHandling();

    $this->post(route('auth.login'), ['user_name' => $this->sales_rep->user_name, 'password' => 'pass', 'remember' => true])
      ->assertHeader('x-inertia-location', route('salesrep.dashboard'))
      ->assertStatus(409);
  }

  /** @test  */
  public function unverified_sales_rep_will_be_required_to_set_a_password_on_login()
  {
    $this->withoutExceptionHandling();

    $sales_rep = SalesRep::factory()->active()->create(['password' => 'pass']);

    $this->post(route('auth.login'), ['user_name' => $sales_rep->user_name, 'password' => 'pass', 'remember' => true])
      ->assertRedirect(route('auth.login'))
      ->assertSessionHas('flash.action_required');
  }

  /** @test */
  public function unverified_sales_rep_can_set_password()
  {
    $this->withoutExceptionHandling();
    $sales_rep = SalesRep::factory()->active()->create();

    $this->post(route('auth.password'), ['email' => $sales_rep->email, 'password' => 'pass'])->assertSessionHas('flash.success', 'Password set successfully! Login using your new credentials.');
  }

  /** @test */
  public function password_is_required_for_sales_rep_to_set_password()
  {
    $sales_rep = SalesRep::factory()->active()->create(['password' => 'pass']);

    $this->post(route('auth.password'), ['email' => $sales_rep->email])->assertSessionHasErrors(['err' => 'A new password is required for your account.']);
  }

  /** @test */
  public function verified_sales_rep_cannot_set_password()
  {
    $sales_rep = SalesRep::factory()->verified()->active()->create(['password' => 'pass']);

    $this->post(route('auth.password'), ['email' => $sales_rep->email, 'password' => 'pass'])->assertSessionHas('flash.error', 'Unauthorised');
  }

  /** @test  */
  public function unauthorized_users_can_not_create_sales_rep()
  {
    $this->assertCount(0, SalesRep::all());

    $this->actingAs(Supervisor::factory()->create(), 'supervisor')->post(route('salesrep.create'), $this->data_to_create_staff())->assertRedirect(route('auth.login'));
    $this->assertCount(0, SalesRep::all());
    $this->actingAs(SalesRep::factory()->create(), 'sales_rep')->post(route('salesrep.create'), $this->data_to_create_staff())->assertRedirect(route('auth.login'));

    $this->assertCount(1, SalesRep::all());
  }

  /** @test */
  public function sales_rep_can_visit_dashboard()
  {
    $rsp = $this->actingAs($this->sales_rep, 'sales_rep')->get(route('salesrep.dashboard'))->assertOk();

    $page = $this->getResponseData($rsp);

    $this->assertArrayHasKey('errors', (array)$page->props);
    $this->assertArrayHasKey('app', (array)$page->props);
    $this->assertArrayHasKey('routes', (array)$page->props);
    $this->assertArrayHasKey('isInertiaRequest', (array)$page->props);
    $this->assertArrayHasKey('auth', (array)$page->props);
    $this->assertArrayHasKey('flash', (array)$page->props);
    // $this->assertCount(19, (array)$page->props->sales_reps);
  }

  /** @test */
  public function sales_rep_can_view_customners_account()
  {
    $this->actingAs($this->sales_rep, 'sales_rep')->get(route('customer.lsit'));
  }

  /** @test */
  public function sales_rep_can_create_customner_account()
  {
    $this->actingAs($this->sales_rep, 'sales_rep')->post;
  }

}
