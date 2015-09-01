<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScholarshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholarships', function (Blueprint $table) {
            $table->increments('id');
            $table->string('scholarship');
            $table->string('name');
            $table->string('email');
            $table->text('document');
            $table->text('comments')->nullable();
            $table->boolean('accepted')->default(0);
            $table->integer('updated_by')->unsigned();
            $table->timestamps();

            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('scholarships');
    }
}
