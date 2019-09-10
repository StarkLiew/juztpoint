<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserCreateProducts extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up($id, Model $model) {
		"_masters";

		Schema::create("user_{$id}_products", function (Blueprint $table) use ($id) {
			$table->bigIncrements('id');
			$table->string('name');
			$table->string('type')->nullable()->index();
			$table->string('status')->nullable()->index();
			$table->bigInteger('cat_id')->unsigned();
			$table->string('sku')->unique();
			$table->bigInteger('tax_id')->unsigned();
			$table->boolean('sellable')->default(false);
			$table->boolean('consumable')->default(false);
			$table->boolean('allow_assistant')->default(false);
			$table->float('discount')->default(0.0);
			$table->boolean('stockable')->default(false);
			$table->json('variants')->nullable();
			$table->json('composites')->nullable();
			$table->bigInteger('commission_id')->unsigned();
			$table->json('properties')->nullable();
			$table->longText('note')->nullable();
			$table->bigInteger('user_id')->unsigned();
			$table->timestamps();
			$table->softDeletes();

			$settings = "user_{$id}_settings";

			$table->foreign('cat_id')
				->references('id')->on($settings)
				->onUpdate('cascade');

			$table->foreign('commission_id')
				->references('id')->on($settings)
				->onUpdate('cascade');

			$table->foreign('tax_id')
				->references('id')->on($settings)
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
	public function down($id, Model $model) {
		Schema::dropIfExists("user_{$id}_products");
	}
}
