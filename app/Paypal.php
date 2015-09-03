<?php

namespace Cupa;

use Illuminate\Database\Eloquent\Model;

class Paypal extends Model
{
    protected $table = 'paypals';
    protected $fillable = [
        'league_member_id',
        'type',
        'league_id',
        'tournament_id',
        'tournament_team_id',
        'payment_id',
        'state',
        'token',
        'payer_id',
        'data',
        'success',
    ];
}
