<?php

namespace Cupa\Http\Requests;

use Cupa\Models\Team;
use Illuminate\Support\Facades\Gate;

class TeamAddEditRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->route('name') !== null) {
            $team = Team::fetchByName($this->route('name'));

            return Gate::allows('edit', $team);
        }

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
            'display_name' => 'required',
            'logo' => 'mimes:jpg,jpeg,png,gif',
            'menu' => 'required',
            'type' => 'required',
            'captains' => 'required',
            'override_email' => 'email',
            'begin' => 'required|numeric',
            'end' => 'numeric',
            'description' => 'required',
        ];
    }
}
