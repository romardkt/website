<?php

namespace Cupa\Http\Requests;

use Config;

class UserRegisterRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $ageRanges = Config::get('cupa.ages');

        return [
            'email' => 'required|email|unique:users',
            'first_name' => 'required',
            'last_name' => 'required',
            'birthday' => 'required|date|before:'.date('Y-m-d', strtotime('-'.$ageRanges['min'].' years')).'|after:'.date('Y-m-d', strtotime('-'.$ageRanges['max'].' years')),
            'gender' => 'required',
            'password' => 'required|confirmed',
            'g-recaptcha-response' => 'required|captcha',
        ];
    }

    public function messages()
    {
        $ageRanges = Config::get('cupa.ages');

        return [
            'birthday.before' => 'You must be at least '.$ageRanges['min'].' years old to register',
            'birthday.after' => 'Age is limited to '.$ageRanges['max'].' years old',
            'g-recaptcha-response.required' => 'Please click the captcha to verify you\'re human',
            'g-recaptcha-response.captcha' => 'Please click the captcha to verify you\'re human',
        ];
    }
}
