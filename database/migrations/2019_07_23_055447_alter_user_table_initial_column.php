<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUserTableInitialColumn extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('users', function (Blueprint $table) {
			$table->string('initial')->nullable();
			$table->string('api_token')->nullable();
			$table->string('pin')->nullable();
			$table->integer('level')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('users', function (Blueprint $table) {
			$table->dropColumn('initial');
			$table->dropColumn('api_token');
			$table->dropColumn('pin');
			$table->dropColumn('level');

		});
	}
}
