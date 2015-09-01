<?php

use Illuminate\Database\Migrations\Migration;

class CreateUserBalancesView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW `user_balances` AS SELECT u.id, u.id AS user_id, GROUP_CONCAT(lm.league_id) AS leagues, SUM(lr.cost) AS balance
            FROM users u
            LEFT JOIN league_members lm ON lm.user_id = u.id
            LEFT JOIN leagues l ON l.id = lm.league_id
            LEFT JOIN league_registrations lr ON lr.league_id = lm.league_id
            LEFT JOIN league_locations ll ON ll.league_id = lm.league_id
            WHERE lr.cost > 0 AND lm.paid = 0 AND lm.position = 'player' AND ll.type = 'league' AND ll.begin <= date_add(NOW(), INTERVAL 1 week)
            GROUP BY u.id");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW `user_balances`');
    }
}
