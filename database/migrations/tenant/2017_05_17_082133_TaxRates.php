<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TaxRates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       if (Schema::hasTable('tax_rates')) return;
       Schema::create('tax_rates', function (Blueprint $table) {
            
            $table->increments('id');
            $table->string('name', 36);
            $table->string('code', 36);
            $table->float('rate')->default(0.0);
            $table->string('country', 5);
            $table->longText('note')->nullable();
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
            Schema::dropIfExists('tax_rates');
    }
}
