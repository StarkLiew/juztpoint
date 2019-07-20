<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CommissionRates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
    {
         if (Schema::hasTable('commission_rates')) return;
        Schema::create('commission_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 36);
            $table->boolean('fix')->default(false);
            $table->float('rate')->default(0.0);
            $table->integer('user_id' )->unsigned();
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
       Schema::dropIfExists('commission_rates');
    }
}
