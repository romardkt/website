<?php

namespace Cupa\Http\Requests;

use Illuminate\Support\Facades\Auth;

class VolunteerShowSignupRequest extends Request
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
        $event = $this->route('event');
        $this->rules = [];
        foreach (json_decode($event->category->questions) as $question) {
            $rule = null;
            if (isset($question->required) && $question->required) {
                $rule = 'required';
            }

            switch ($question->type) {
                case 'radio':
                    $rule .= '|in:'.implode(',', array_keys((array) $question->answers));
                    break;
            }

            if ($rule !== null) {
                $this->rules[$question->name] = $rule;
            }
        }

        return $this->rules;
    }

    public function messages()
    {
        $messages = [];
        if (isset($this->rules['current_league'])) {
            $messages['current_league.required_if'] = 'Please enter the league you want to help with.';
        }

        if (isset($this->rules['new_league'])) {
            $messages['new_league.required_if'] = 'Please enter another league you might want to help/start.';
        }

        return $messages;
    }
}
