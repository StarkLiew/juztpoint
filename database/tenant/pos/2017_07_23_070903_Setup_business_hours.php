<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetupBusinessHours extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
   if (Schema::hasTable('business_hours')) return;
        Schema::create('business_hours', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('store_id')->unsigned();
            $table->date('time_from');
            $table->date('time_to');
            $table->string('day',10);
            $table->string('status',5);
            $table->integer('user_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('store_id')
                  ->references('id')->on('stores')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            
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
        Schema::dropIfExists('business_hours');
    }
}