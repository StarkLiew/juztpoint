<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserCreateItems extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up($id, Model $model) {

		Schema::create("user_{$id}_items", function (Blueprint $table) use ($id) {
			$table->increments('id');
			$table->bigInteger('line');
			$table->string('type');
			$table->bigInteger('trxn_id')->unsigned();
			$table->bigInteger('item_id')->unsigned();
			$table->json('discount');
			$table->float('discount_amount');
			$table->bigInteger('tax_id');
			$table->float('tax_amount');
			$table->float('total_amount');
			$table->longText('note')->nullable();
			$table->bigInteger('user_id')->unsigned();
			$table->timestamps();
			$table->softDeletes();

			$products = "user_{$id}_products";
			$settings = "user_{$id}_settings";

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
		Schema::dropIfExists("user_{$id}_items");
	}
}
