<?php

namespace Cupa\Http\Requests;

use Illuminate\Support\Facades\Gate;

class LeagueAddRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('is-manager');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'type' => 'required|not_in:0',
            'year' => 'required|not_in:0',
            'league_type' => 'required',
        ];

        if ($this->get('league_type') == 0) {
            $rules['season'] = 'required|not_in:0';
            $rules['directors'] = 'required';
            $rules['day'] = 'required|not_in:0';
        } else {
            $rules['copy'] = 'required|not_in:0';
        }

        return $rules;
    }
}
