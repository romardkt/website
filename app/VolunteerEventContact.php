<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VolunteerEventContact extends Model
{
    protected $table = 'volunteer_event_contacts';
    protected $fillable = [
        'volunteer_event_id',
        'user_id',
        'email_override',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function updateContacts($volunteerEventId, $contacts)
    {
        $dbContacts = [];
        foreach (static::where('volunteer_event_id', '=', $volunteerEventId)->get() as $contact) {
            $dbContacts[] = $contact->user_id;
        }

        $subContacts = [];
        foreach ($contacts as $contactId) {
            $subContacts[] = $contactId;
        }

        $remove = array_diff($dbContacts, $subContacts);
        if (count($remove)) {
            DB::table('volunteer_event_contacts')->where('volunteer_event_id', '=', $volunteerEventId)->whereIn('user_id', $remove)->delete();
        }

        $add = array_diff($subContacts, $dbContacts);
        foreach ($add as $a) {
            static::create([
                'volunteer_event_id' => $volunteerEventId,
                'user_id' => $a,
            ]);
        }

        return;
    }
}
