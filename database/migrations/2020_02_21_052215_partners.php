<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class Partners extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('announcements', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedBigInteger('user_id');
			$table->string('name');
			$table->string('company');
			$table->string('phone');
			$table->string('mobile');
			$table->string('interest');
			$table->string('email')->unique();
			$table->string('code');
			$table->boolean('ban')->default(0);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('partners');
	}
}
