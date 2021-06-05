<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDirectSwapTransactionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('direct_swap_transactions', function (Blueprint $table) {
      $table->id();
      $table->foreignId('fz_customer_id')->constrained();
      $table->foreignId('sales_rep_id')->constrained('fz_staff');
      $table->foreignId('fz_product_type_id')->constrained();
      $table->foreignId('company_bank_account_id')->nullable()->constrained();
      $table->integer('quantity');
      $table->decimal('amount');
      $table->enum('customer_paid_via', ['cash', 'bank']);


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
    Schema::dropIfExists('direct_swap_transactions');
  }
}
