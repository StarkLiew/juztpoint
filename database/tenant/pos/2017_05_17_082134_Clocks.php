<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Clocks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
 public function up()
    {
        if (Schema::hasTable('clocks')) return;
        Schema::create('clocks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->enum('stamp',['IN','OUT']);
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
        Schema::dropIfExists('clocks');
    }
}
