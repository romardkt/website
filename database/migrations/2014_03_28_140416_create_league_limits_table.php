<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeagueLimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('league_limits', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('league_id')->unsigned();
            $table->integer('male')->unsigned()->nullable();
            $table->integer('female')->unsigned()->nullable();
            $table->integer('total')->unsigned()->nullable();
            $table->integer('teams')->unsigned()->nullable();
            $table->text('players')->nullable();
            $table->timestamps();

            $table->foreign('league_id')->references('id')->on('leagues')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('league_limits');
    }
}
