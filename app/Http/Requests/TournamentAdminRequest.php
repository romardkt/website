<?php

namespace Cupa\Http\Requests;

use Cupa\Tournament;
use Illuminate\Support\Facades\Gate;

class TournamentAdminRequest extends Request
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
            'display_name' => 'required',
            'override_email' => 'email',
            'divisions' => 'required|array',
            'start' => 'required|date',
            'end' => 'required|date',
            'header' => 'mimes:jpg,png,gif,jpeg',
        ];
    }
}
