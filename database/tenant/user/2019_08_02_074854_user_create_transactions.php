<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserCreateTransactions extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up($id, Model $model) {

		Schema::create("user_{$id}_transactions", function (Blueprint $table) use ($id) {
			$table->increments('id');
			$table->string('reference', 36)->unique()->index();
			$table->bigInteger('account_id')->unsigned()->index();
			$table->bigInteger('transact_by')->unsigned()->index();
			$table->timestamp('date');
			$table->string('type');
			$table->string('status');
			$table->json('content');
			$table->float('amount');
			$table->longText('note')->nullable();
			$table->timestamps();
			$table->softDeletes();

			$accounts = "user_{$id}_accounts";
			$table->foreign('account_id')
				->references('id')->on($accounts)
				->onUpdate('cascade');

			$table->foreign('transact_by')
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
		Schema::dropIfExists("user_{$id}_transactions");
	}
}
