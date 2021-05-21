<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppUsersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('app_users', function (Blueprint $table) {
      $table->id();
      $table->string('email')->unique();
      $table->string('password');
      $table->timestamp('email_verified_at')->nullable();
      $table->string('first_name');
      $table->string('last_name')->nullable();
      $table->string('phone')->unique();
      $table->timestamp('otp_verified_at')->nullable();
      $table->string('address');
      $table->string('city');
      $table->string('ig_handle')->unique()->nullable();
      $table->string('avatar')->nullable();
      $table->boolean('is_active')->default(true);

      $table->rememberToken();
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
    Schema::dropIfExists('app_users');
  }
}
