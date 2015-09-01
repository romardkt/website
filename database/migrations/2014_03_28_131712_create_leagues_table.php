<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leagues', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('type')->length(25);
            $table->integer('year')->unsigned();
            $table->string('season')->length(15);
            $table->string('day')->length(15);
            $table->string('name')->nullable();
            $table->string('slug');
            $table->string('override_email')->nullable();
            $table->boolean('user_teams')->default(0);
            $table->boolean('has_pods')->default(0);
            $table->boolean('is_youth')->default(0);
            $table->boolean('has_waitlist')->default(1);
            $table->text('description');
            $table->datetime('date_visible')->nullable();
            $table->boolean('is_archived');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('leagues');
    }
}
