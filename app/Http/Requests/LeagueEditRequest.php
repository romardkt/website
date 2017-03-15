<?php

namespace Cupa\Http\Requests;

use Gate;
use Cupa\Models\League;

class LeagueEditRequest extends Request
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
        $rules = [];
        if ($this->method() == 'GET') {
            return $rules;
        }

        switch ($this->type) {
            case 'description':
                $rules['description'] = 'required';
                break;
            case 'information':
                $rules['directors'] = 'required';
                $rules['league_location_id'] = 'required|numeric|not_in:0';
                $rules['league_start_date'] = 'required|date';
                $rules['league_start_time'] = 'required|regex:/1?[0-9]:[0-9][0-9]/';
                $rules['league_end_date'] = 'required|date';
                $rules['league_end_time'] = 'required|regex:/1?[0-9]:[0-9][0-9]/';

                if ($this->get('is_draft')) {
                    $rules['draft_location_id'] = 'required|numeric|not_in:0';
                    $rules['draft_start_date'] = 'required|date';
                    $rules['draft_start_time'] = 'required|regex:/1?[0-9]:[0-9][0-9]/';
                    $rules['draft_end_date'] = 'required|date';
                    $rules['draft_end_time'] = 'required|regex:/1?[0-9]:[0-9][0-9]/';
                }

                if ($this->get('is_tournament')) {
                    $rules['tournament_location_id'] = 'required|numeric|not_in:0';
                    $rules['tournament_start_date'] = 'required|date';
                    $rules['tournament_start_time'] = 'required|regex:/1?[0-9]:[0-9][0-9]/';
                    $rules['tournament_end_date'] = 'required|date';
                    $rules['tournament_end_time'] = 'required|regex:/1?[0-9]:[0-9][0-9]/';
                }
                break;
            case 'registration':
                $rules['start_date'] = 'required|date';
                $rules['start_time'] = 'required|regex:/1?[0-9]:[0-9][0-9]/';
                $rules['end_date'] = 'required|date';
                $rules['end_time'] = 'required|regex:/1?[0-9]:[0-9][0-9]/';
                $rules['male'] = 'integer|min:1';
                $rules['female'] = 'integer|min:1';
                $rules['total'] = 'integer|min:1';
                $rules['teams'] = 'integer|min:1';
                $rules['cost'] = 'integer|min:0';
                $rules['cost_female'] = 'integer|min:0';
                break;
            case 'registration_questions':
                $rules['question'] = 'required';
                break;
            case 'settings':
                $league = League::fetchBySlug($this->route('slug'));
                $rules['date_visible'] = 'date';
                $rules['override_email'] = 'email';
                $rules['slug'] = 'unique:leagues,slug,'.$league->id;
                break;
        }

        return $rules;
    }
}
