<?php

namespace Cupa\Http\Requests;

use Cupa\Tournament;
use Illuminate\Support\Facades\Gate;

class TournamentBidEditRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $tournament = Tournament::fetchTournament($this->route('name'), $this->route('year'));

        return Gate::allows('edit', $tournament);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cost' => 'required|numeric',
            'bid_due_date' => 'required|date',
            'bid_due_time' => 'required',
            'paypal_type' => 'required|in:0,1,2',
        ];
    }
}
