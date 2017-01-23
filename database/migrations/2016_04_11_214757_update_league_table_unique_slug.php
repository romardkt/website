<?php

use Illuminate\Database\Migrations\Migration;

class UpdateLeagueTableUniqueSlug extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('leagues', function ($table) {
            $table->string('slug')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('leagues', function ($table) {
            $table->string('slug')->change();
        });
    }
}
