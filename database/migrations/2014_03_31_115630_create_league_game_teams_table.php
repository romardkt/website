<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeagueGameTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('league_game_teams', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('league_game_id')->unsigned();
            $table->string('type')->length(10);
            $table->integer('league_team_id')->unsigned();
            $table->integer('score')->unsigned();
            $table->timestamps();

            $table->foreign('league_game_id')->references('id')->on('league_games')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('league_team_id')->references('id')->on('league_teams')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('league_game_teams');
    }
}
