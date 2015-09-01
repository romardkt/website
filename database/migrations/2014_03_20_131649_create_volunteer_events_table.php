<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVolunteerEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('volunteer_events', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('volunteer_event_category_id')->unsigned();
            $table->string('title');
            $table->string('slug');
            $table->string('email_override')->nullable();
            $table->datetime('start');
            $table->string('end');
            $table->integer('num_volunteers')->unsigned()->nullable();
            $table->text('information');
            $table->integer('location_id')->unsigned();
            $table->timestamps();

            $table->foreign('volunteer_event_category_id')->references('id')->on('volunteer_event_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('locations')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('volunteer_events');
    }
}
