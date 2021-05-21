<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductReceiptsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('product_receipts', function (Blueprint $table) {
      $table->id();
      $table->foreignId('product_id')->constrained();
      $table->string('user_email');
      $table->string('user_phone');
      $table->string('user_address');
      $table->string('user_city');
      $table->string('order_ref');
      $table->double('amount_paid');
      $table->double('tax_rate')->nullable();
      $table->double('delivery_fee')->nullable();

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
    Schema::dropIfExists('product_receipts');
  }
}
