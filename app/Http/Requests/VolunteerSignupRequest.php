<?php

namespace Cupa\Http\Requests;

use Config;

class VolunteerSignupRequest extends Request
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
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|regex:/^\d\d\d-\d\d\d-\d\d\d\d$/',
            'birthday' => 'required|date|before:'.date('Y-m-d', strtotime('-'.$ageRanges['min'].' years')).'|after:'.date('Y-m-d', strtotime('-'.$ageRanges['max'].' years')),
            'gender' => 'required',
            'involvement' => 'required',
            'primary_interest' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'phone' => 'Please enter a valid phone number',
        ];
    }
}
