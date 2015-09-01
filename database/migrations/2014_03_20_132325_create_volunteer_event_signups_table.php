<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVolunteerEventSignupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('volunteer_event_signups', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('volunteer_event_id')->unsigned();
            $table->integer('volunteer_id')->unsigned();
            $table->text('answers');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('volunteer_event_id')->references('id')->on('volunteer_events')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('volunteer_id')->references('id')->on('volunteers')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('volunteer_event_signups');
    }
}
