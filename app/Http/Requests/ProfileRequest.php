<?php

namespace Cupa\Http\Requests;

use Illuminate\Support\Facades\Auth;

class ProfileRequest extends Request
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
        return [
            'email' => 'required|email|unique:users,email,'.Auth::id(),
            'first_name' => 'required',
            'last_name' => 'required',
            'birthday' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'phone' => 'required|regex:/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/',
            'avatar' => 'mimes:jpg,png,gif,jpeg',
        ];
    }
}