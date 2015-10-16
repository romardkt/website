<?php

namespace Cupa\Http\Requests;

use Illuminate\Support\Facades\Auth;

class ProfileContactRequest extends Request
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
            'name' => 'required',
            'phone' => 'required|regex:/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/',
        ];
    }
}
