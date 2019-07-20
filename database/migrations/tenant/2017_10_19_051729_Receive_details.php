<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReceiveDetails extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		if (Schema::hasTable('receive_details')) {
			return;
		}

		Schema::create('receive_details', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('line')->unsigned()->index();
			$table->integer('receive_id')->unsigned()->index();
			$table->integer('product_id')->unsigned()->index();
			$table->integer('user_id')->unsigned()->nullable()->index();
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

			$table->foreign('receive_id')
				->references('id')->on('receives')
				->onUpdate('cascade')
				->onDelete('cascade');

			$table->foreign('product_id')
				->references('id')->on('products')
				->onUpdate('cascade');

			$table->foreign('tax_id')
				->references('id')->on('tax_rates')
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
		Schema::dropIfExists('receive_details');
	}
}
