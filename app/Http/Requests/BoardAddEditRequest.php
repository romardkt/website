<?php

namespace Cupa\Http\Requests;

use Gate;

class BoardAddEditRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('is-editor');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|integer',
            'position' => 'required|not_in:0',
            'started' => 'required|date',
            'stopped' => 'date',
            'description' => 'required',
            'avatar' => 'mimes:jpg,png,gif,jpeg',
        ];
    }

    public function messages()
    {
        return [
            'position' => 'Please select a position',
        ];
    }
}
