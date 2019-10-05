<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserCreateSettings extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up($id, Model $model) {

		Schema::create("user_{$id}_settings", function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('name');
			$table->bigInteger('subscription_id')->unsigned()->nullable();
			$table->string('description')->nullable();
			$table->string('type');
			$table->json('properties')->nullable();
			$table->timestamps();
			$table->bigInteger('user_id')->unsigned();
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
		Schema::dropIfExists("user_{$id}_settings");
	}
}
