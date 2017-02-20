<?php

namespace Cupa\Http\Requests;

use Cupa\Models\Tournament;
use Illuminate\Support\Facades\Gate;

class TournamentContactRequest extends Request
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
            'user_id' => 'required|integer',
            'position' => 'required',
        ];
    }
}
