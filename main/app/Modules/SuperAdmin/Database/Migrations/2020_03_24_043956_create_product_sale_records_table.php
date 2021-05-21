<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSaleRecordsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_sale_records', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('product_id');
      $table->string('product_type');
      $table->unique(['product_id', 'product_type'], 'unique_product_sale_record');
			$table->double('selling_price');
      $table->double('online_rep_bonus')->default(0);
      $table->double('walk_in_rep_bonus')->default(0);
      $table->foreignId('sales_channel_id')->nullable()->constrained('sales_channels')->onDelete('cascade');
      $table->foreignId('online_rep_id')->nullable()->constrained('sales_reps')->onDelete('cascade');
      $table->unsignedBigInteger('sales_rep_id')->nullable();
      $table->string('sales_rep_type')->nullable();
			$table->unsignedBigInteger('sale_confirmed_by')->nullable();
			$table->string('sale_confirmer_type')->nullable();
      $table->boolean('is_swap_transaction')->default(false);


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
		Schema::dropIfExists('product_sale_records');
	}
}
