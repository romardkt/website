<?php

namespace Cupa\Http\Requests;

use Illuminate\Support\Facades\Gate;

class HoyScholarshipUpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('is-hoy-scholarship');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        //     'accepted' => 'required',
        //     'comments' => 'required',
        ];
    }
}
