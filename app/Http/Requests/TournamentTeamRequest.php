<?php

namespace Cupa\Http\Requests;

use Cupa\Models\Tournament;
use Illuminate\Support\Facades\Gate;

class TournamentTeamRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $tournamentTeam = $this->route('team');
        if (!$tournamentTeam) {
            $tournament = Tournament::fetchTournament($this->route('name'), $this->route('year'));
        } else {
            $tournament = $tournamentTeam->tournament;
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
            'division' => 'required',
            'name' => 'required',
            'city' => 'required',
            'state' => 'required|size:2',
            'contact_name' => 'required',
            'contact_phone' => 'required|regex:/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/',
            'contact_email' => 'required|email',
        ];
    }
}
