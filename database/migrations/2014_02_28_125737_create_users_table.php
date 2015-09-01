<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('parent')->unsigned()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('salt')->nullable();
            $table->string('password')->nullable();
            $table->string('first_name')->length(50);
            $table->string('last_name')->length(50);
            $table->string('gender')->length(15);
            $table->date('birthday')->nullable();
            $table->string('avatar')->default('/data/users/default.png');
            $table->string('activation_code')->length(50);
            $table->datetime('activated_at')->nullable();
            $table->string('reset_password_code')->length(50)->nullable();
            $table->datetime('last_reset_password_at')->nullable();
            $table->datetime('last_login_at')->nullable();
            $table->text('reason')->nullable();
            $table->string('remember_token')->nullable();
            $table->boolean('is_active')->default(0);
            $table->timestamps();

            $table->foreign('parent')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
