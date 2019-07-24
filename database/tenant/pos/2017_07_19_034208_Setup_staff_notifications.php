<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetupStaffNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('config_staff_notifications')) return;
        Schema::create('config_staff_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('enable_notification')->default(true);
            $table->boolean('send_to_staff')->default(true);
            $table->boolean('send_to_specific_email')->default(false);
            $table->string('specific_email')->nullable();
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
        Schema::dropIfExists('config_staff_notifications');
    }
}
