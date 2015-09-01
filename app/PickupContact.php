<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class PickupContact extends Model
{
    protected $table = 'pickup_contacts';
    protected $fillable = [
        'pickup_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('Cupa\User');
    }
}
