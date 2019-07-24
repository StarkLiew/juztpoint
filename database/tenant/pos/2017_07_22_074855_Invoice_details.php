<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InvoiceDetails extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		if (Schema::hasTable('invoice_details')) {
			return;
		}

		Schema::create('invoice_details', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('line')->unsigned()->index();
			$table->integer('invoice_id')->unsigned()->index();
			$table->integer('appointment_id')->unsigned()->nullable()->index();
			$table->integer('product_id')->unsigned()->index();
			$table->integer('customer_id')->unsigned()->nullable()->index();
			$table->integer('user_id')->unsigned()->nullable()->index();
			$table->integer('assistant_id')->unsigned()->nullable();
			$table->float('qty')->nullable()->default(0);
			$table->float('price')->nullable()->default(0);
			$table->float('discount')->nullable()->default(0);
			$table->string('disType')->nullable()->default(0);
			$table->float('total')->nullable()->default(0);
			$table->integer('tax_id')->unsigned()->nullable();
			$table->float('tax_rate')->nullable()->default(0);
			$table->float('tax')->nullable()->default(0);
			$table->float('amount')->nullable()->default(0);
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('invoice_id')
				->references('id')->on('invoices')
				->onUpdate('cascade')
				->onDelete('cascade');

			$table->foreign('appointment_id')
				->references('id')->on('appointments')
				->onUpdate('cascade')
				->onDelete('cascade');

			$table->foreign('product_id')
				->references('id')->on('products')
				->onUpdate('cascade');

			$table->foreign('tax_id')
				->references('id')->on('tax_rates')
				->onUpdate('cascade');

			$table->foreign('customer_id')
				->references('id')->on('accounts')
				->onUpdate('cascade');

			$table->foreign('user_id')
				->references('id')->on('users')
				->onUpdate('cascade');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('invoice_details');
	}
}
