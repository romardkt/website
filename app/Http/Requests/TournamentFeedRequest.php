<?php

namespace Cupa\Http\Requests;

use Cupa\Tournament;
use Illuminate\Support\Facades\Gate;

class TournamentFeedRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $tournamentFeed = $this->route('feed');
        if (!$tournamentFeed) {
            $tournament = Tournament::fetchTournament($this->route('name'), $this->route('year'));
        } else {
            $tournament = $tournamentFeed->tournament;
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
            'content' => 'required',
        ];
    }
}
