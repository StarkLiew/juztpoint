<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Receives extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('receives')) return;
        Schema::create('receives', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference',36)->unique()->index();
            $table->integer('account_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->timestamp('date');
            $table->string('type');
            $table->float('gross')->nullable()->default(0);
            $table->float('discount')->nullable()->default(0);
            $table->float('tax')->nullable()->default(0);
            $table->float('nett')->nullable()->default(0);
            $table->longText('note')->nullable();
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
        Schema::dropIfExists('receives');
    }
}