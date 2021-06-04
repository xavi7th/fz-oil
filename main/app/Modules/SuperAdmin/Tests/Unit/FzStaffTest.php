<?php

namespace App\Modules\SuperAdmin\Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FzStaffTest extends TestCase
{
  use RefreshDatabase;

  /** @test  */
  public function fz_staff_database_has_expected_columns()
  {
    $this->assertTrue(
      Schema::hasColumns('fz_staff', [
        'id', 'email', 'user_name', 'password', 'full_name', 'phone', 'address', 'id_url', 'is_active', 'staff_role_id', 'gender', 'created_at', 'updated_at', 'deleted_at', 'last_login_at'
      ]),
      1
    );
    $this->assertJsonStringEqualsJsonString(
      '["id","email","user_name","password","full_name","gender","phone","address","id_url","is_active","staff_role_id","remember_token","last_login_at","verified_at","created_at","updated_at","deleted_at"]',
      json_encode(Schema::getColumnListing('fz_staff'))
    );
  }


}
