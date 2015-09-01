<?php

use Illuminate\Database\Migrations\Migration;

class CreateLeagueCountsView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW `league_player_counts` AS SELECT l.id, l.id AS league_id, COUNT(lmt.id) AS total, COUNT(um.id) AS male, COUNT(uf.id) AS female, COUNT(lmt.id) - (COUNT(um.id) + COUNT(uf.id)) AS other
            FROM leagues l
            LEFT JOIN league_members lmt ON lmt.league_id = l.id AND lmt.position = 'player'
            LEFT JOIN users um ON um.id = lmt.user_id AND um.gender = 'Male'
            LEFT JOIN users uf ON uf.id = lmt.user_id AND uf.gender = 'Female'
            GROUP BY l.id");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW `league_player_counts`');
    }
}
