<?php

namespace Cupa\Http\Requests;

use Cupa\Models\League;
use Illuminate\Support\Facades\Gate;

class LeagueCoachEmailRequest extends Request
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
            'from' => 'required',
            'name' => 'required',
            'subject' => 'required',
        ];
    }
}
