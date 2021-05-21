<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function (Blueprint $table) {
			$table->bigIncrements('id');
      $table->foreignId('app_user_id')->nullable()->constrained('app_users')->onDelete('cascade');
      $table->foreignId('product_category_id')->constrained()->onDelete('cascade');
      $table->foreignId('product_model_id')->constrained()->onDelete('cascade');
      $table->foreignId('product_brand_id')->constrained()->onDelete('cascade');
      $table->foreignId('product_batch_id')->constrained()->onDelete('cascade');
      $table->foreignId('product_color_id')->constrained()->onDelete('cascade');
      $table->foreignId('product_grade_id')->constrained()->onDelete('cascade');
      $table->foreignId('product_supplier_id')->constrained()->onDelete('cascade');
      $table->foreignId('storage_size_id')->constrained()->onDelete('cascade');
      $table->foreignId('ram_size_id')->nullable()->constrained('storage_sizes')->onDelete('cascade');
      $table->foreignId('storage_type_id')->nullable()->constrained()->onDelete('cascade');
      $table->foreignId('processor_speed_id')->nullable()->constrained()->onDelete('cascade');
			$table->string('imei')->unique()->nullable();
			$table->string('serial_no')->unique()->nullable();
			$table->string('model_no')->unique()->nullable();
      $table->foreignId('product_status_id')->default(1)->constrained('product_statuses')->onDelete('cascade');
      $table->timestamp('sold_at')->nullable();
			$table->uuid('product_uuid');
			$table->unsignedBigInteger('stocked_by');
			$table->string('stocker_type');

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
		Schema::dropIfExists('products');
	}
}
