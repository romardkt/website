<?php

namespace Cupa\Http\Requests;

use Gate;

class MinuteAddEditRequest extends Request
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
            'location_id' => 'required|numeric|not_in:0',
            'start_date' => 'required|date',
            'start_time' => 'required|regex:/1?[0-9]:[0-9][0-9]/',
            'end_date' => 'required|date',
            'end_time' => 'required|regex:/1?[0-9]:[0-9][0-9]/',
            'pdf' => 'mimes:pdf',
        ];
    }
}
