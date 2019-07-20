<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Appointments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('appointments')) return;
        Schema::create('appointments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned();
            $table->timestamp('appoint_datetime');
            $table->integer('status')->unsigned()->nullable();
            $table->integer('user_id')->unsigned();
            $table->longText('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')
                  ->references('id')->on('accounts')
                  ->onUpdate('cascade')
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
        Schema::dropIfExists('appointments');
    }
}

