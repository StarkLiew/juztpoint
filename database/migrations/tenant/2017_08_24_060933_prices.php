<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Prices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      if (Schema::hasTable('prices')) return;
        Schema::create('prices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('prod_id')->unsigned()->nullable()->index();
            $table->float('price')->default(0.0);
            $table->float('qty')->default(0.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prices');
    }
}