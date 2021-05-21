<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasedItemsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('purchased_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('product_sale_record_id')->constrained();
      $table->unsignedBigInteger('purchased_item_id');
      $table->string('purchased_item_type');
      $table->decimal('selling_price', 15, 2);
      $table->integer('purchased_quantity')->unsigned();

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('purchased_items');
  }
}
