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
        return [
            'fullname' => 'required|in:'.Auth::user()->fullname(),
            'read' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'fullname.in' => 'You must enter your name exactly as it appears in the wavier',
            'fullname.required' => 'You must enter your name exactly as it appears in the wavier',
            'read.required' => 'Please check the box to verify you read the waiver',
        ];
    }
}
