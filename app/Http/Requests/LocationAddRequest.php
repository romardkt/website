<?php

namespace Cupa\Http\Requests;

use Illuminate\Contracts\Validation\Validator;

class LocationAddRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->ajax();
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
            'street' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please enter a location name',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function formatErrors(Validator $validator)
    {
        return ['status' => 'error', 'message' => $validator->messages()->first()];
    }
}
