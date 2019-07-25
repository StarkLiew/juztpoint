<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PosCreateTransaction extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		Schema::create('transactions', function (Blueprint $table) {
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

			$table->foreign('account_id')
				->references('id')->on('masters')
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
	public function down() {
		Schema::dropIfExists('transactions');
	}
}
