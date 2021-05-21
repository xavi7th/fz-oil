<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyBankAccountsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('company_bank_accounts', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('bank');
			$table->string('account_name');
			$table->string('account_number');
			$table->string('account_type');
			$table->string('img_url')->nullable();
			$table->text('account_description')->nullable();

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
		Schema::dropIfExists('company_bank_accounts');
	}
}
