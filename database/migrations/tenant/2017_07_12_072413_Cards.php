<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Cards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('cards')) return;
        Schema::create('cards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 36);
            $table->integer('user_id')->unsigned();
            $table->timestamps();
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
    public function down()
    {
          Schema::dropIfExists('cards');
    }
}
