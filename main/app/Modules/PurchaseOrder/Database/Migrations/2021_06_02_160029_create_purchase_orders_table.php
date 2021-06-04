<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrdersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('purchase_orders', function (Blueprint $table) {
      $table->id();
      $table->string('payment_type');
      $table->foreignId('fz_customer_id')->constrained();
      $table->foreignId('fz_product_type_id')->constrained();
      $table->foreignId('fz_price_batch_id');
      $table->foreignId('sales_rep_id')->constrained('fz_staff');
      $table->integer('purchased_quantity');
      $table->decimal('total_selling_price', 15, 2);
      $table->boolean('is_swap_transaction')->default(false);
      $table->foreignId('swap_product_type_id')->nullable()->constrained('fz_product_types');
      $table->integer('swap_quantity')->nullable();
      $table->decimal('swap_value', 10, 2)->nullable();
      $table->decimal('total_amount_paid', 15, 2);
      $table->boolean('is_lodged')->default(true);

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
    Schema::dropIfExists('purchase_orders');
  }
}
