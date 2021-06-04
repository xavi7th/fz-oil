<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFzStockTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('fz_stock', function (Blueprint $table) {
      $table->id();
      $table->foreignId('fz_product_type_id')->constrained();
      $table->foreignId('fz_price_batch_id')->constrained();
      $table->integer('stock_quantity');
      $table->unique(['fz_product_type_id', 'fz_price_batch_id']);

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
    Schema::dropIfExists('fz_stock');
  }
}
