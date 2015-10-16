<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TournamentMember extends Model
{
    protected $table = 'tournament_members';
    protected $fillable = [
        'tournament_id',
        'user_id',
        'position',
        'weight',
    ];

    public function tournament()
    {
        return $this->belongsTo('Cupa\Tournament');
    }

    public function user()
    {
        return $this->belongsTo('Cupa\User');
    }

    public static function updateMembers($tournamentId, $members, $position)
    {
        // get all members in the database
        $dbMembers = [];
        foreach (static::where('tournament_id', '=', $tournamentId)->where('position', '=', $position)->get() as $member) {
            $dbMembers[] = $member->user_id;
        }

        // build list of contact ids to check
        $subMembers = [];
        foreach ($members as $memberId) {
            $subMembers[] = $memberId;
        }

        // build the list of members to remove
        $remove = array_diff($dbMembers, $subMembers);
        if (count($remove)) {
            // remove the members
            DB::table('tournament_members')->where('tournament_id', '=', $tournamentId)->where('position', '=', $position)->whereIn('user_id', $remove)->delete();
        }

        // add the members that are left
        $add = array_diff($subMembers, $dbMembers);
        foreach ($add as $a) {
            static::create([
                'tournament_id' => $tournamentId,
                'user_id' => $a,
                'position' => $position,
                'weight' => static::getWeight($tournamentId) + 1,
            ]);
        }

        return;
    }

    public static function getWeight($tournamentId)
    {
        return static::where('tournament_id', '=', $tournamentId)->max('weight');
    }
}
