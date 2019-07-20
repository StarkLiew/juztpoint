<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Products extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
  {
        if(Schema::hasTable('products')) return;
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('cat_id')->unsigned()->nullable()->index();
            $table->string('sku')->nullable()->index();
            $table->integer('tax_id')->unsigned()->nullable();
            $table->boolean('sellable')->default(false);
            $table->boolean('service')->default(false);
            $table->boolean('consumable')->default(false);
            $table->boolean('assistant')->default(false);
            $table->float('discount')->default(0.0);
            $table->boolean('stock')->default(false);
            $table->integer('commission_id')->unsigned()->index();
            $table->longText('note')->nullable();
            $table->integer('user_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cat_id')
                  ->references('id')->on('categories')
                  ->onUpdate('cascade');

            $table->foreign('commission_id')
                  ->references('id')->on('commission_rates')
                  ->onUpdate('cascade');

            $table->foreign('tax_id')
                  ->references('id')->on('tax_rates')
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
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
