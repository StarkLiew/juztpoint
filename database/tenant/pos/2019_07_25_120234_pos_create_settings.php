<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PosCreateSettings extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		Schema::create('settings', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('name');
			$table->string('description');
			$table->string('type');
			$table->json('data');
			$table->timestamps();
			$table->bigInteger('user_id')->unsigned()->index();
		});

		$table->foreign('user_id')
			->references('id')->on('users')
			->onUpdate('cascade');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('settings');
	}
}
