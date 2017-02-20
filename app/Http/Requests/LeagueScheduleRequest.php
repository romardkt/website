<?php

namespace Cupa\Http\Requests;

use Cupa\Models\League;
use Illuminate\Support\Facades\Gate;

class LeagueScheduleRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $league = League::fetchBySlug($this->route('slug'));

        return Gate::allows('edit', $league);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'played_at_date' => 'required|date',
            'played_at_time' => 'required|regex:/1?[0-9]:[0-9][0-9]/',
            'week' => 'required|numeric',
            'field' => 'required|numeric',
            'status' => 'required',
            'away_team' => 'required_if:status,game_on|array',
            'home_team' => 'required_if:status,game_on|array',
            'away_score' => 'numeric',
            'home_score' => 'numeric',
        ];
    }
}
