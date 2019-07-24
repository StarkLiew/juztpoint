<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LayoutCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          if (Schema::hasTable('layout_categories')) return;
         Schema::create('layout_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('layout_id')->unsigned()->index();
            $table->boolean('service')->default(false)->nullable();
             $table->integer('user_id' )->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onUpdate('cascade');

            $table->foreign('layout_id')
                  ->references('id')->on('layouts')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('layout_categories');
    }
}
