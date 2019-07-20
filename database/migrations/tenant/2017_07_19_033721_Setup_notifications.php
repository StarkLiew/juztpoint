<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetupNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('config_notifications')) return;
        Schema::create('config_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('enable_notification')->default(true);
            $table->double('notification_delay');
            $table->boolean('enable_reminders')->default(true);
            $table->double('advance_reminder');
            $table->string('reminders_subject')->nullable();
            $table->longText('reminders_message')->nullable();
            $table->string('reminders_sms',150)->nullable();
            $table->boolean('enable_conformation')->default(true);
            $table->string('conformation_subject')->nullable();
            $table->longText('conformation_message')->nullable();
            $table->string('conformation_sms',150)->nullable();
            $table->boolean('enable_reschedules')->default(true);
            $table->string('reschedules_subject')->nullable();
            $table->longText('reschedules_message')->nullable();
            $table->string('reschedule_sms',150)->nullable();
            $table->boolean('enable_cancelations')->default(true);
            $table->string('cancelations_subject')->nullable();
            $table->longText('cancelations_message')->nullable();
            $table->string('cancelations_sms',150)->nullable();
            $table->boolean('enable_thanks')->default(true);
            $table->string('thanks_subject')->nullable();
            $table->longText('thanks_message')->nullable();
            $table->string('thanks_sms',150)->nullable();
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
        Schema::dropIfExists('config_notification');
    }
}