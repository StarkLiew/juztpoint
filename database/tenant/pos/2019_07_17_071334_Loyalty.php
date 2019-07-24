<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Loyalty extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		if (Schema::hasTable('loyalty')) {
			return;
		}

		Schema::create('loyalty', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->integer('point')->default(1);
			$table->integer('equal')->default(1);
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
		Schema::dropIfExists('loyalty');
	}
}
