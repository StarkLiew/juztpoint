<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetupCompany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('companies')) return;
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('address')->nullable();
            $table->string('website')->nullable();
            $table->string('contact_number',100)->nullable();
            $table->string('country',2);
            $table->boolean('online_booking')->default(false)->nullable();
            $table->string('timezone',50);
            $table->string('currency',5);
            $table->string('time_format',20);
            $table->integer('user_id')->unsigned();
            $table->string('lock_customer',36)->nullable();
            $table->string('lock_payment_type',36)->nullable();
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
        Schema::dropIfExists('company');
    }
}