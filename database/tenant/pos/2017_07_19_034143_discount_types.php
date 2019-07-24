<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DiscountTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if (Schema::hasTable('discount_types')) return;
        Schema::create('discount_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->float('value');
            $table->boolean('fix');
            $table->boolean('service_sales')->nullable()->default(true);
            $table->boolean('product_sales')->nullable()->default(true);
            $table->boolean('voucher_sales')->nullable()->default(true);
            $table->integer('user_id')->unsigned();
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
        Schema::dropIfExists('discount_types');
    }
}