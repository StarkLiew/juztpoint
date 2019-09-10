<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserCreateDocuments extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up($id, Model $model) {

		Schema::create("user_{$id}_documents", function (Blueprint $table) use ($id) {
			$table->increments('id');
			$table->string('reference', 36)->unique()->index();
			$table->string('account_id')->nullable();
			$table->bigInteger('transact_by')->unsigned();
			$table->bigInteger('terminal_id')->unsigned();
			$table->timestamp('date');
			$table->string('type');
			$table->string('status');
			$table->json('discount');
			$table->float('discount_amount');
			$table->float('service_charge');
			$table->float('tax_amount');
			$table->float('rounding');
			$table->float('charge');
			$table->float('received');
			$table->float('change');
			$table->float('refund');
			$table->json('properties')->nullable();
			$table->longText('note')->nullable();
			$table->timestamps();
			$table->softDeletes();

			$accounts = "user_{$id}_accounts";

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
		Schema::dropIfExists("user_{$id}_documents");
	}
}
