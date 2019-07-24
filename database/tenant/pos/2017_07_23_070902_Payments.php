<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Payments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if (Schema::hasTable('payments')) return;
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pay_type_id')->unsigned()->index();
            $table->integer('invoice_id')->unsigned()->index();
            $table->string('reference',36)->nullable()->index();
            $table->integer('voucher_id')->unsigned()->nullable()->index();
            $table->integer('card_type_id')->unsigned()->nullable()->index();
            $table->float('amount')->default(0.00);
            $table->float('change')->default(0.00);
            $table->float('received')->default(0.00);
            $table->longText('note')->nullable();
            $table->boolean('paid')->default(false);
            $table->integer('print')->unsigned();
            $table->integer('email')->unsigned();
            $table->timestamp('pay_date');
            $table->integer('user_id')->unsigned()->index();
            $table->timestamps();
            $table->softDeletes();
                  
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onUpdate('cascade');

            $table->foreign('voucher_id')
                  ->references('id')->on('vouchers')
                  ->onUpdate('cascade');

             $table->foreign('pay_type_id')
                  ->references('id')->on('payment_types')
                  ->onUpdate('cascade');

             $table->foreign('card_type_id')
                  ->references('id')->on('cards')
                  ->onUpdate('cascade');


             $table->foreign('invoice_id')
                  ->references('id')->on('invoices')
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
          Schema::dropIfExists('payments');
    }
}
