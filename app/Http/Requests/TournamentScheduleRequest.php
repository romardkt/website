<?php

namespace Cupa\Http\Requests;

use Illuminate\Support\Facades\Gate;

class TournamentScheduleRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('edit', $this->route('tournament_id'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'schedule' => 'required',
        ];
    }
}
