<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFzPriceBatchesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('fz_price_batches', function (Blueprint $table) {
      $table->id();
      $table->foreignId('fz_product_type_id')->constrained();
      $table->decimal('cost_price', 8, 2);
      $table->decimal('selling_price', 8, 2);
      $table->unique(['fz_product_type_id', 'cost_price']);

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
    Schema::dropIfExists('fz_price_batches');
  }
}
