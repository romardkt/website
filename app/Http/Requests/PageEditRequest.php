<?php

namespace Cupa\Http\Requests;

use Gate;

class PageEditRequest extends Request
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
            'display' => 'required',
            'content' => 'required',
        ];
    }
}
