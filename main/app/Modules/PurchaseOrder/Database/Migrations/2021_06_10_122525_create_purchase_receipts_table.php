<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseReceiptsTable extends Migration
{
  /**
  * Run the migrations.
  *
  * @return void
  */
  public function up()
  {
    Schema::create('purchase_receipts', function (Blueprint $table) {
      $table->id();
      $table->foreignId('purchase_order_id')->constrained();
      $table->foreignId('sales_rep_id')->constrained('fz_staff');
      $table->foreignId('fz_product_type_id')->constrained();
      $table->string('product'); //oil, gallon
      $table->string('cashier_name');
      $table->string('payment_type'); // Cash transfer or credit
      $table->integer('quantity');
      $table->decimal('transaction_amount', 15,2);
      $table->decimal('amount_tendered', 15,2);
      $table->decimal('change_received', 15,2);
      $table->boolean('is_printed')->default(false);

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
    Schema::dropIfExists('purchase_receipts');
  }
}
