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
      $table->foreignId('fz_staff_id')->nullable()->constrained();
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
      $table->foreignId('product_status_id')->default(1)->constrained();
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
