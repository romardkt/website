<?php

namespace Cupa\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PickupAddEditRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $contacts = explode(',', $this->get('contacts'));

        return Gate::allows('is-editor') || in_array(Auth::id(), $contacts);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'day' => 'required',
            'time' => 'required',
            'contacts' => 'required',
            'is_visible' => 'required',
            'info' => 'required',
            'location_id' => 'required|numeric|not_in:0',
            'email_override' => 'email',
        ];
    }
}
