<?php

namespace Cupa\Http\Requests;

use Illuminate\Support\Facades\Gate;

class TournamentAddRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('is-manager');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required_without:new_name',
            'new_name' => 'required_if:name,0',
            'year' => 'numeric',
            'directors' => 'required',
            'email_override' => 'email',
            'divisions' => 'required',
            'paypal' => 'required_if:use_paypal,0',
            'start_date' => 'required|date',
            'start_time' => 'required|regex:/1?[0-9]:[0-9][0-9]/',
            'end_date' => 'required|date',
            'end_time' => 'required|regex:/1?[0-9]:[0-9][0-9]/',
            'location_id' => 'required|numeric|not_in:0',
            'description' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'new_name.required_if' => 'Please enter a name for the tournament',
        ];
    }
}
