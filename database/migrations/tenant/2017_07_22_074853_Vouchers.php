<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Vouchers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('vouchers')) return;
        Schema::create('vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('code');
            $table->date('expire')->nullable();
            $table->longText('note')->nullable();
            $table->integer('account_id')->unsigned()->index();
            $table->integer('user_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('account_id')
                  ->references('id')->on('accounts')
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
          Schema::dropIfExists('vouchers');
    }
}
