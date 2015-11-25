<?php

namespace Cupa\Http\Requests;

use Cupa\League;
use Cupa\LeagueQuestion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LeagueRegistrationRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        if ($this->method == 'GET') {
            return $rules;
        }

        switch ($this->state) {
            case 'who':
                $rules['user'] = 'required|numeric';
                break;
            case 'info':
                $session = Session::get('league_registration');
                $userId = ($session->registrant->parent !== null) ? $session->registrant->parentObj->id : $session->registrant->id;
                $rules['email'] = 'required|email|unique:users,email,'.$userId;
                $rules['first_name'] = 'required';
                $rules['last_name'] = 'required';
                $rules['birthday'] = 'required|date';
                $rules['gender'] = 'required';
                $rules['phone'] = 'required|regex:/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/';
                $rules['level'] = 'required';
                $rules['height'] = 'required|integer|min:24|max:108';
                $rules['experience'] = 'required|integer|min:1950|max:'.date('Y');

                break;
            case 'contact':
                break;
            case 'league':
                $league = League::fetchBySlug($this->route('slug'));
                if ($league->user_teams) {
                    $rules['user_teams'] = 'required|not_in:0';
                }

                foreach (json_decode($league->registration()->first()->questions) as $i => $questionData) {
                    list($questionId, $required) = explode('-', $questionData);
                    $question = LeagueQuestion::find($questionId);
                    if ($required == 1) {
                        $rules[$question->name] = 'required';
                    }
                }

                break;
            case 'finish':
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'user.required' => 'Please select a player to register with',
            'phone' => 'Please enter a valid phone number',
            'user_teams.not_in' => 'Please select a team',
        ];
    }
}
