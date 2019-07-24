<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetupSales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('config_sales')) return;
        Schema::create('config_sales', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('price_include_tax')->nullable()->default(false);
            $table->boolean('auto_print_tax_invoice')->nullable()->default(true);
            $table->string('tax_invoice_msg');
            $table->boolean('calc_staff_commission_before_discount')->nullable()->default(true);
            $table->boolean('calc_staff_commission_include_tax')->nullable()->default(false);
            $table->double('voucher_expire')->nullable()->default(false);
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
        Schema::dropIfExists('config_sales');
    }
}