<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PosCreateProducts extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		Schema::create('products', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('name');
			$table->bigInteger('cat_id')->unsigned()->nullable()->index();
			$table->string('sku')->nullable()->index();
			$table->integer('tax_id')->unsigned()->nullable();
			$table->boolean('sellable')->default(false);
			$table->boolean('service')->default(false);
			$table->boolean('consumable')->default(false);
			$table->boolean('assistant')->default(false);
			$table->float('discount')->default(0.0);
			$table->boolean('stock')->default(false);
			$table->json('variants')->nullable();
			$table->json('composites')->nullable();
			$table->bigInteger('commission_id')->unsigned()->index();
			$table->json('params')->nullable();
			$table->longText('note')->nullable();
			$table->integer('user_id')->unsigned();
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('cat_id')
				->references('id')->on('masters')
				->onUpdate('cascade');

			$table->foreign('commission_id')
				->references('id')->on('masters')
				->onUpdate('cascade');

			$table->foreign('tax_id')
				->references('id')->on('masters')
				->onUpdate('cascade');

			$table->foreign('assistant')
				->references('id')->on('users')
				->onUpdate('cascade');
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
	public function down() {
		Schema::dropIfExists('products');
	}
}
