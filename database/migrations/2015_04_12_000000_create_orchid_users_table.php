<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrchidUsersTable extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up() {
		Schema::table('users', function (Blueprint $table) {
			$table->timestamp('last_login')->nullable();
			$table->jsonb('permissions')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down() {
		Schema::dropIfExists('users', function (Blueprint $table) {
			$table->dropColumn('last_login');
			$table->dropColumn('permissions');
		});
	}
}
