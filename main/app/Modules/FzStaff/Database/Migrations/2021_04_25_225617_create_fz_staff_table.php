<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFzStaffTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('fz_staff', function (Blueprint $table) {
      $table->id();
      $table->string('email')->unique();
      $table->string('user_name')->unique();
      $table->string('password');
      $table->string('full_name');
      $table->string('gender');
      $table->string('phone')->unique();
      $table->string('address');
      $table->string('id_url')->nullable();
      $table->boolean('is_active')->default(false);
      $table->foreignId('staff_role_id')->constrained();

      $table->rememberToken();
      $table->timestamp('last_login_at')->nullable();
      $table->timestamp('verified_at')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('fz_staff');
  }
}
