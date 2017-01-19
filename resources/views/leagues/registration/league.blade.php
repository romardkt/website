<div class="row">
    <div class="col-xs-12 col-sm-offset-2 col-sm-8">
        @include('partials.errors')

        {!! Form::open(['class' => 'form form-vertical', 'role' => 'form', 'novalidate']) !!}

        <legend>League Questions</legend>

        @if ($league->user_teams && count($league->teams))
        <div class="form-group">
            {!! Form::label('Select a team') !!}
            {!! Form::select('user_teams', [0 => 'Select a Team'] + $league->fetchTeamsForSelect(), null, ['class' => 'form-control']) !!}
            <span class="help-block">Select a team to join</span>
        </div>
        @endif

        @foreach(json_decode($league->registration->questions) as $i => $questionData)
        <?php list($questionId, $required) = explode('-', $questionData); ?>
        <?php $question = Cupa\LeagueQuestion::find($questionId); ?>
        <?php $i = ($i + 1).'.) '; ?>
        <div class="form-group">
            @if ($question->type == 'descriptive')
            {!! Form::label($i) !!}
            {!!$question->title!!}
            @else
            {!! Form::label($i . $question->title) !!}
            @if ($question->type == 'text')
            {!! Form::text($question->name, null, ['class' => 'form-control', 'required']) !!}
            @elseif ($question->type == 'boolean')
            <div class="checkbox">
                {!! Form::radio($question->name, 'No', null) !!} No
                &nbsp;&nbsp;&nbsp;&nbsp;
                {!! Form::radio($question->name, 'Yes', null) !!} Yes
            </div>
            @elseif ($question->type == 'multiple')
                @foreach(json_decode($question->answers, true) as $value => $answer)
            <div class="checkbox">
                {!! Form::radio($question->name, $value, false) !!} {{ $answer }}
            </div>
                @endforeach
            @elseif ($question->type == 'multiple-check')
                @foreach(json_decode($question->answers, true) as $value => $answer)
            <div class="checkbox multiple">
                {!! Form::checkbox($question->name.'[]', $value, false) !!} {{ $answer }}
            </div>
                @endforeach
            @elseif ($question->type == 'textarea')
            {!! Form::textarea($question->name, null, ['class' => 'form-control']) !!}
            @endif
            <span class="help-block">{{ ($required != 1) ? 'Optional' : '' }}</span>
            @endif
        </div>
        @endforeach
        <hr>
        <div class="form-group">
            <div class="col-xs-12 text-center">
                <a class="btn btn-default" href="{{ route('league_register', [$league->slug, 'contacts']) }}"><i class="fa fa-fw fa-lg fa-arrow-left"></i> Back</a>
                <button type="submit" class="btn btn-primary">Next <i class="fa fa-fw fa-lg fa-arrow-right"></i></button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
