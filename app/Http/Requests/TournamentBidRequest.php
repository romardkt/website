<?php

namespace Cupa\Http\Requests;

use Cupa\Tournament;
use Illuminate\Support\Facades\Gate;

class TournamentBidRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $tournament = Tournament::fetchTournament($this->route('name'), $this->route('year'));

        return Gate::allows('show', $tournament);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'division' => 'required',
            'name' => 'required',
            'city' => 'required',
            'state' => 'required|size:2',
            'contact_name' => 'required',
            'contact_phone' => 'required|regex:/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/',
            'contact_email' => 'required|email',
            'g-recaptcha-response' => 'required|captcha',
        ];
    }

    public function messages()
    {
        return [
            'g-recaptcha-response.required' => 'Please click the captcha to verify you\'re human',
            'g-recaptcha-response.captcha' => 'Please click the captcha to verify you\'re human',
        ];
    }
}
