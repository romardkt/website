<?php

namespace Cupa\Http\Requests;

use Gate;

class FormAddEditRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('is-admin');
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
            'year' => 'required|integer',
            'document' => 'required|mimes:pdf,docx,doc,xls,xlsx',
        ];
    }
}
