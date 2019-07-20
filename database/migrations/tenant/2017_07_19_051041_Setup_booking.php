<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetupBooking extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		if (Schema::hasTable('config_bookings')) {
			return;
		}

		Schema::create('config_bookings', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('max_book_hour');
			$table->integer('max_book_month');
			$table->integer('max_cancel_hour');
			$table->string('policy')->nullable();
			$table->boolean('choose_staff')->default(true);
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
		Schema::dropIfExists('config_bookings');
	}
}