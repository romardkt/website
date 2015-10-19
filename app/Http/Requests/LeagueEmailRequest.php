<?php

namespace Cupa\Http\Requests;

use Illuminate\Support\Facades\Auth;

class LeagueEmailRequest extends Request
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
        $rules = [
            'to' => 'required',
            'from' => 'required',
            'name' => 'required',
            'subject' => 'required',
            'body' => 'required',
        ];

        if (Auth::guest()) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'to.required' => 'Please select at least one person to send the message to',
        ];

        if (Auth::guest()) {
            $messages['g-recaptcha-response.required'] = 'Please click the captcha to verify you\'re human';
            $messages['g-recaptcha-response.captcha'] = 'Please click the captcha to verify you\'re human';
        }

        return $messages;
    }
}
