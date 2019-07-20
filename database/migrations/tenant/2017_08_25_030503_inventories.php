<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Inventories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
  {
        if (Schema::hasTable('inventories')) return;
        Schema::create('inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ref_prod_id')->unsigned()->index();
            $table->integer('prod_id')->unsigned()->index();
            $table->float('unit')->default(1.0);
            $table->timestamps();

            $table->foreign('prod_id')
                  ->references('id')->on('products')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('ref_prod_id')
                  ->references('id')->on('products')
                  ->onDelete('cascade')
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
        Schema::dropIfExists('inventories');
    }
}
