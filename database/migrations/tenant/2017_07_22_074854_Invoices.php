<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Invoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('invoices')) return;
        Schema::create('invoices', function (Blueprint $table) {
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
            $table->float('received')->nullable()->default(0);
            $table->float('change')->nullable()->default(0);
            $table->string('payment_type')->nullable()->default(0);
            $table->string('cardpay_ref')->nullable()->default(0);
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
        Schema::dropIfExists('invoices');
    }
}
