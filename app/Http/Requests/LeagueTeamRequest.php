<?php

namespace Cupa\Http\Requests;

use Cupa\Models\League;
use Illuminate\Support\Facades\Gate;

class LeagueTeamRequest extends Request
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
        $rules = [
            'name' => 'required',
            'color' => 'required',
            'color_code' => 'required',
            'logo' => 'mimes:jpg,png,gif,jpeg',
        ];

        $league = League::fetchBySlug($this->route('slug'));
        if ($league->is_youth) {
            $rules['head_coaches'] = 'required';
        } else {
            $rules['captains'] = 'required';
        }

        return $rules;
    }
}
