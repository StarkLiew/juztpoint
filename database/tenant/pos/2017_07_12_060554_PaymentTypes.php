<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PaymentTypes extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		if (Schema::hasTable('payment_types')) {
			return;
		}

		Schema::create('payment_types', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('code', 36);
			$table->longText('note')->nullable();
			$table->integer('user_id')->unsigned();
			$table->timestamps();
			$table->softDeletes();

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
		Schema::dropIfExists('payment_types');
	}
}
