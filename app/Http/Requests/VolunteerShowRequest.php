<?php

namespace Cupa\Http\Requests;

use Illuminate\Support\Facades\Gate;

class VolunteerShowRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('is-volunteer');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category' => 'required|integer|min:1',
            'title' => 'required',
            'contacts' => 'required',
            'email_override' => 'email',
            'start_date' => 'required|date',
            'start_time' => 'required|regex:/1?[0-9]:[0-9][0-9]/',
            'end_date' => 'required|date',
            'end_time' => 'required|regex:/1?[0-9]:[0-9][0-9]/',
            'num_volunteers' => 'required|integer|min:1',
            'information' => 'required',
            'volunteer_id' => 'min:1',
            'location_id' => 'required||numeric|not_in:0',
        ];
    }

    public function messages()
    {
        return [
            'category.min' => 'Please select a category',
            'contacts.required' => 'Please enter at least one contact',
        ];
    }
}
