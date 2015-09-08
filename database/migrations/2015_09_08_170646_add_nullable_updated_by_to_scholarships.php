<?php

use Illuminate\Database\Migrations\Migration;

class AddNullableUpdatedByToScholarships extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('scholarships', function ($table) {
            $table->dropForeign('scholarships_updated_by_foreign');
            $table->integer('updated_by')->unsigned()->nullable()->change();
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('scholarships', function ($table) {
            $table->dropForeign('scholarships_updated_by_foreign');
            $table->integer('updated_by')->unsigned()->change();
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }
}
