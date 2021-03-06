<?php

namespace Cupa\Http\Requests;

use Illuminate\Support\Facades\Auth;

class LeagueRegistrationSuccessRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'player' => 'required:not_in:0',
        ];
    }
}
