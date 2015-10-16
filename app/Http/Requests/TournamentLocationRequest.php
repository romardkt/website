<?php

namespace Cupa\Http\Requests;

use Cupa\Tournament;
use Illuminate\Support\Facades\Gate;

class TournamentLocationRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $tournamentLocation = $this->route('tournament_location_id');
        if (!$tournamentLocation) {
            $tournament = Tournament::fetchTournament($this->route('name'), $this->route('year'));
        } else {
            $tournament = $tournamentLocation->tournament;
        }

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
            'title' => 'required',
            'phone' => 'regex:/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/',
            'state' => 'size:2',
            'zip' => 'size:5',
        ];
    }
}
