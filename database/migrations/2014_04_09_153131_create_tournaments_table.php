<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->integer('year');
            $table->string('display_name');
            $table->string('override_email')->nullable();
            $table->string('image')->default('/data/tournaments/default.jpg');
            $table->text('divisions')->nullable();
            $table->integer('location_id')->unsigned();
            $table->date('start');
            $table->date('end');
            $table->text('description');
            $table->text('schedule')->nullable();
            //$table->string('score_reporter')->nullable();
            $table->boolean('tenative_date')->default(0);
            $table->boolean('use_bid')->default(1);
            $table->integer('cost')->unsigned();
            $table->datetime('bid_due');
            $table->boolean('use_paypal')->default(1);
            $table->boolean('has_teams')->default(1);
            $table->text('paypal')->nullable();
            $table->text('mail')->nullable();
            $table->boolean('is_visible')->default(0);
            $table->timestamps();

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
        Schema::drop('tournaments');
    }
}
