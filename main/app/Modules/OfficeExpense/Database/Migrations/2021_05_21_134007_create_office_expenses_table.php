<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfficeExpensesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('office_expenses', function (Blueprint $table) {
      $table->id();
      $table->foreignId('sales_rep_id')->constrained('fz_staff');
      $table->decimal('amount', 8, 2);
      $table->string('payment_type');
      $table->text('description');
      $table->string('expense_date');

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
    Schema::dropIfExists('office_expenses');
  }
}
