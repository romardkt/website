<?php

namespace Cupa\Http\Requests;

use Illuminate\Support\Facades\Gate;

class ClinicEditRequest extends Request
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
            'name' => 'required',
            'display' => 'required',
            'content' => 'required',
        ];
    }
}
