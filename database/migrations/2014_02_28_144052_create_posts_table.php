<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('category')->length(25);
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->text('content')->nullable();
            $table->integer('posted_by')->unsigned();
            $table->datetime('post_at');
            $table->datetime('remove_at')->nullable();
            $table->boolean('is_featured')->default(0);
            $table->boolean('is_visible')->default(0);
            $table->timestamps();

            $table->foreign('posted_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('posts');
    }
}
