<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditTransactionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('credit_transactions', function (Blueprint $table) {
      $table->id();
      $table->foreignId('fz_customer_id')->constrained();
      $table->foreignId('recorded_by')->constrained('fz_staff');
      $table->string('trans_type');
      $table->decimal('amount', 10, 2);
      $table->date('trans_date');
      $table->string('payment_type');
      $table->foreignId('company_bank_account_id')->nullable()->constrained();
      $table->boolean('is_lodged')->default(true);

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
    Schema::dropIfExists('credit_transactions');
  }
}
