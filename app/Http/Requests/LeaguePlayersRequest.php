<?php

namespace Cupa\Http\Requests;

use Gate;

class LeaguePlayersRequest extends Request
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
        return [
            'source_player' => 'required|numeric',
            'to_team' => 'required|numeric',
            'to' => 'required|numeric',
        ];
    }
}
