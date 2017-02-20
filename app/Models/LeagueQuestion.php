<?php

namespace Cupa\Models;

use Illuminate\Database\Eloquent\Model;

class LeagueQuestion extends Model
{
    protected $table = 'league_questions';
    protected $fillable = [
        'name',
        'title',
        'type',
        'answers',
    ];

    public static function fetchQuestions($currentQuestions)
    {
        $dbQuestions = [];
        foreach (static::orderBy('name')->get() as $question) {
            $dbQuestions[$question->id] = $question;
        }

        foreach ($currentQuestions as $i => $question) {
            list($questionId, $required) = explode('-', $question);
            $q = $dbQuestions[$questionId];
            $q->required = $required;

            $questions[] = $q;
        }

        return $questions;
    }

    public static function fetchAllQuestions($currentQuestions = null)
    {
        if ($currentQuestions === null) {
            return static::orderBy('name')->get();
        }

        $cQuestions = [];
        foreach ($currentQuestions as $i => $question) {
            list($questionId, $required) = explode('-', $question);
            $cQuestions[] = $questionId;
        }

        $questionIds = [];
        foreach (static::orderBy('name')->get() as $question) {
            if (!in_array($question->id, $cQuestions) && $question->name != 'user_teams') {
                $questionIds[] = $question->id;
            }
        }

        return static::whereIn('id', $questionIds)->get();
    }
}
