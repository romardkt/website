<?php

namespace Cupa\Http\Requests;

use Cupa\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class WaiverRequest extends Request
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
        $user = $this->route('user');
        if (!$user) {
            $user = Auth::user();
        }

        if ($user->getAge() < 18) {
            return [
                'ice2_name' => 'required',
                'ice2_email' => 'required|email',
                'ice2_phone' => 'required|regex:/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/',
                'ice3_name' => 'required',
                'ice3_phone' => 'required|regex:/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/',
                'physician_name' => 'required',
                'physician_phone' => 'required|regex:/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/',
            ];
        } else {
            return [
                'fullname' => 'required|in:'.$user->fullname(),
                'read' => 'required',
            ];
        }
    }

    public function messages()
    {
        return [
            'fullname.in' => 'You must enter your name EXACTLY as it appears in the wavier (with capitals)',
            'fullname.required' => 'You must enter your name EXACTLY as it appears in the wavier (with capitals)',
            'read.required' => 'Please check the box to verify you read the waiver',
            'ice2_name.required' => 'A second parent/guardian contact is required',
            'ice2_email.required' => 'A second parent/guardian contact email is required',
            'ice2_phone.required' => 'A second parent/guardian contact phone number is required',
            'ice2_phone.regex' => 'A second parent/guardian contact phone number in format ###-###-####',
            'ice3_name.required' => 'An emergency contact is required',
            'ice3_phone.required' => 'An emergency contact phone number is required',
            'ice3_phone.regex' => 'An emergency contact phone number in format ###-###-####',
            'physician_name.required' => 'A family physician is required',
            'physician_phone.required' => 'A family physician phone number is required',
            'physician_phone.regex' => 'An family physician phone number in format ###-###-####',
        ];
    }
}
