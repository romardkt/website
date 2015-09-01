<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaypalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paypals', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('league_member_id')->unsigned()->nullable();
            $table->string('type')->length(100);
            $table->integer('league_id')->unsigned()->nullable();
            $table->integer('tournament_id')->unsigned()->nullable();
            $table->integer('tournament_team_id')->unsigned()->nullable();
            $table->string('payment_id')->nullable();
            $table->string('state')->nullable();
            $table->string('token')->nullable();
            $table->string('payer_id')->nullable();
            $table->text('data')->nullable();
            $table->boolean('success')->default(0);
            $table->timestamps();

            $table->foreign('league_member_id')->references('id')->on('league_members')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('league_id')->references('id')->on('leagues')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('tournament_id')->references('id')->on('tournaments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('tournament_team_id')->references('id')->on('tournament_teams')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('paypals');
    }
}
