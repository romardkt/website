<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentTeamsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tournament_teams', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('tournament_id')->unsigned();
            $table->string('division');
            $table->string('name');
            $table->string('city');
            $table->string('state');
            $table->string('contact_name');
            $table->string('contact_phone');
            $table->string('contact_email');
            $table->boolean('accepted')->default(0);
            $table->boolean('paid')->default(0);
            $table->text('comments')->nullable();
            $table->timestamps();

            $table->foreign('tournament_id')->references('id')->on('tournaments')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('tournament_teams');
    }
}
