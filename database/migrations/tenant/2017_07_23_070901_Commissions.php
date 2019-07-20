<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Commissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
    {


        if (Schema::hasTable('commissions')) return;
        Schema::create('commissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('inv_detail_id')->unsigned()->index();
            $table->integer('appointment_detail_id')->unsigned()->nullable()->index();
            $table->integer('user_id')->unsigned();
            $table->float('amount')->default(0.0);
            $table->timestamps();
            $table->softDeletes();
                           
            $table->foreign('inv_detail_id')
                  ->references('id')->on('invoice_details')
                      ->onUpdate('cascade')
                      ->onDelete('cascade');

            $table->foreign('appointment_detail_id')
                  ->references('id')->on('appointment_details')
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
       Schema::dropIfExists('commissions');
    }
}
