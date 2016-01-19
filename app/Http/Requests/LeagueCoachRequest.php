<?php

namespace Cupa\Http\Requests;

use Cupa\League;
use Illuminate\Support\Facades\Gate;

class LeagueCoachRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $league = League::fetchBySlug($this->route('slug'));

        return Gate::allows('coach', $league);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $member = $this->route('member');

        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,'.$member->user->id,
            'phone' => 'required|regex:/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/',
        ];
    }
}
