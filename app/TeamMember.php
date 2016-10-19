<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TeamMember extends Model
{
    protected $table = 'team_members';
    protected $fillable = [
        'team_id',
        'user_id',
        'year',
        'position',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function updateMembers($teamId, $members, $position, $year = null)
    {
        // get all members in the database
        $dbMembers = [];
        foreach (static::where('team_id', '=', $teamId)->where('year', '=', $year)->get() as $member) {
            $dbMembers[] = $member->user_id;
        }

        // build list of member ids to check
        $subMembers = [];
        foreach ($members as $memberId) {
            $subMembers[] = $memberId;
        }

        // build the list of members to remove
        $remove = array_diff($dbMembers, $subMembers);
        if (count($remove)) {
            // remove the members
            DB::table('team_members')->where('team_id', '=', $teamId)->where('year', '=', $year)->where('position', '=', $position)->whereIn('user_id', $remove)->delete();
        }

        // add the members that are left
        $add = array_diff($subMembers, $dbMembers);
        foreach ($add as $a) {
            static::create([
                'team_id' => $teamId,
                'user_id' => $a,
                'position' => $position,
                'year' => $year,
            ]);
        }

        return;
    }
}
