<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PickupContact extends Model
{
    protected $table = 'pickup_contacts';
    protected $fillable = [
        'pickup_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function updateContacts($pickupId, $contacts)
    {
        // get all contacts in the database
        $dbContacts = [];
        foreach (static::where('pickup_id', '=', $pickupId)->get() as $contact) {
            $dbContacts[] = $contact->user_id;
        }

        // build list of contact ids to check
        $subContacts = [];
        foreach ($contacts as $contactId) {
            $subContacts[] = $contactId;
        }

        // build the list of contacts to remove
        $remove = array_diff($dbContacts, $subContacts);
        if (count($remove)) {
            // remove the contacts
            DB::table('pickup_contacts')->where('pickup_id', '=', $pickupId)->whereIn('user_id', $remove)->delete();
        }

        // add the contacts that are left
        $add = array_diff($subContacts, $dbContacts);
        foreach ($add as $a) {
            static::create([
                'pickup_id' => $pickupId,
                'user_id' => $a,
            ]);
        }

        return;
    }
}
