<?php

use Illuminate\Database\Migrations\Migration;

class CreateLeagueTeamRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW `league_team_records` AS SELECT lt.id AS id, lt.id AS league_team_id, SUM(CASE WHEN lgt.score > lgto.score Then 1 Else 0 End) AS wins, SUM(CASE WHEN lgt.score < lgto.score Then 1 Else 0 End) AS losses, IFNULL(SUM(lgt.score), 0) AS points_for, IFNULL(SUM(lgto.score), 0) AS points_against, IFNULL(SUM(lgt.score) - SUM(lgto.score), 0) AS diff
            FROM league_teams lt
            LEFT JOIN league_game_teams lgt ON lgt.league_team_id = lt.id
            LEFT JOIN league_games lg ON lg.id = lgt.league_game_id
            LEFT JOIN league_game_teams lgto ON lgt.league_game_id = lgto.league_game_id AND lgto.type <> lgt.type
            GROUP BY lt.id
            ORDER BY lt.id");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW `league_team_records`');
    }
}
