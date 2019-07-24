<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Stores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('stores')) return;
        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->string('street1');
            $table->string('street2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('country',5);
            $table->string('zipcode', 100);
            $table->string('timezone',50);
            $table->string('currency',5);
            $table->boolean('online_booking')->default(false)->nullable();
            $table->integer('layout_id')->unsigned()->index()->nullable();
            $table->integer('user_id')->unsigned();
            $table->integer('scope_id')->unsigned()->index()->nullable();

            $table->timestamps();
            $table->softDeletes();



            $table->foreign('layout_id')
                  ->references('id')->on('layouts')
                  ->onUpdate('cascade');

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onUpdate('cascade');

         /*   $table->foreign('scope_id')
                  ->references('id')->on('oauth_scopes')
                  ->onUpdate('cascade'); */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
