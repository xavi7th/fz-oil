<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashLodgementsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('cash_lodgements', function (Blueprint $table) {
      $table->id();
      $table->foreignId('company_bank_account_id')->constrained();
      $table->decimal('amount', 10, 2);
      $table->date('lodgement_date');
      $table->string('teller_url');

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
    Schema::dropIfExists('cash_lodgements');
  }
}
