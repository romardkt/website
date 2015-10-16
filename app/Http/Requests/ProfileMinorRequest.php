<?php

namespace Cupa\Http\Requests;

use Illuminate\Support\Facades\Auth;

class ProfileMinorRequest extends Request
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
            'first_name' => 'required',
            'last_name' => 'required',
            'birthday' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'height' => 'required|integer|min:24|max:108',
            'level' => 'required',
            'experience' => 'required|integer|min:1950|max:'.date('Y'),
            'consent' => 'required',
        ];
    }
}
