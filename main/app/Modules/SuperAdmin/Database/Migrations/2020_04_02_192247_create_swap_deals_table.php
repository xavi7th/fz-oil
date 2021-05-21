<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSwapDealsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('swap_deals', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->unsignedBigInteger('app_user_id')->nullable();
      $table->foreign('app_user_id')->references('id')->on('app_users')->onDelete('cascade');
      $table->string('description');
      $table->string('owner_details');
      $table->string('id_url')->nullable();
      $table->string('receipt_url')->nullable();
      $table->string('imei')->nullable();
      $table->string('serial_no')->nullable();
      $table->string('model_no')->nullable();
      $table->double('swap_value');
      $table->double('selling_price')->nullable();
      $table->timestamp('sold_at')->nullable();
      $table->unsignedBigInteger('swapped_with_id')->nullable();
      $table->string('swapped_with_type')->nullable();
      $table->unsignedBigInteger('product_status_id')->default(1);
      $table->foreign('product_status_id')->references('id')->on('product_statuses')->onDelete('cascade');
      $table->uuid('product_uuid');

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
    Schema::dropIfExists('swap_deals');
  }
}
