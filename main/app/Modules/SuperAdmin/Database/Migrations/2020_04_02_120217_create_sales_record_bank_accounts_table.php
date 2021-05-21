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
			$table->unsignedBigInteger('product_sale_record_id')->index();
			$table->foreign('product_sale_record_id')->references('id')->on('product_sale_records')->onDelete('cascade');
			$table->unsignedBigInteger('company_bank_account_id')->index();
			$table->foreign('company_bank_account_id')->references('id')->on('company_bank_accounts')->onDelete('cascade');
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
