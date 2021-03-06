<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserCreateAccounts extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up($id, Model $model) {

		Schema::create("user_{$id}_accounts", function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('uid')->unique()->index();
			$table->string('name');
			$table->string('type');
			$table->string('status');
			$table->json('properties')->nullable();
			$table->bigInteger('user_id')->unsigned();
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
	public function down($id, Model $model) {

		Schema::dropIfExists("user_{$id}_accounts");
	}
}
