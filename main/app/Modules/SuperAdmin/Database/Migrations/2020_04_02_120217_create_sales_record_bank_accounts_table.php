<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesRecordBankAccountsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sales_record_bank_accounts', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->foreignId('product_sale_record_id')->index()->constrained();
			$table->foreignId('company_bank_account_id')->index()->constrained();
			$table->double('amount');

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
		Schema::dropIfExists('sales_record_bank_accounts');
	}
}
