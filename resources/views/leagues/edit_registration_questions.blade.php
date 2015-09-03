@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Edit {{{ $league->displayName() }}} Registration Questions</h2>
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-xs-4 text-left">
        <a class="btn btn-default" href="{{ route('league_edit', [$league->slug, 'registration']) }}"><i class="fa fa-fw fa-lg fa-arrow-left"></i> Back</a>
    </div>
    <div class="col-xs-8 text-right">
        <a class="btn btn-default" href="#" data-toggle="modal" data-target="#add-question"><i class="fa fa-fw fa-lg fa-plus"></i> Add Question</a>
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
            @if ($league->user_teams && count($league->teams))
            {{ Form::select('user_teams', array_merge([0 => 'Select a Team'], $league->fetchTeamsForSelect())) }}
            <span class="help-block">Select a team to join</span>
            @endif
            @foreach($questions as $i => $question)
            <div class="row league-question">
                <div class="col-xs-1 text-center reg-question-count">
                    {{{ $i + 1 }}}.)
                </div>
                <div class="col-xs-11">
                    <div class="row">
                        <div class="col-xs-12 status">
                            Question named <strong class="text-muted">{{{ $question->name }}}</strong>
                            of type
                            <strong class="text-muted">{{{ $question->type }}}</strong>
                            is
                            <strong class="{{{ ($question->required) ? 'text-danger' : 'text-info' }}}">{{{ ($question->required) ? 'Required' : 'Optional' }}}</strong>
                        </div>
                    </div>
                    <div class="row title">
                        <div class="col-xs-12">
                            <div class="form-group">
                                {{ Form::label($question->title) }}
                                @if ($question->type == 'text')
                                {{ Form::text($question->name, null, ['class' => 'form-control', 'disabled']) }}
                                @elseif ($question->type == 'boolean')
                                <div class="checkbox">
                                    {{ Form::radio($question->name, 0, false, ['disabled']) }} No
                                    {{ Form::radio($question->name, 1, false, ['disabled']) }} Yes
                                </div>
                                @elseif ($question->type == 'multiple')
                                @foreach(json_decode($question->answers, true) as $value => $answer)
                                <div class="checkbox disabled">
                                    {{ Form::radio($question->name, $value, null, ['disabled']) }} {{ $answer }}
                                </div>
                                @endforeach
                                @elseif ($question->type == 'textarea')
                                {{ Form::textarea($question->name, null, ['class' => 'form-control', 'disabled']) }}
                                @endif
                                <span class="help-block">{{ (isset($question->required) && $question->required != 1) ? 'Optional' : '' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row actions text-center">
                    <div class="col-xs-12">
                        <p>
                            <div class="btn-group">
                                @if($i > 0)
                                <button class="btn btn-default move-up-btn" data-question="{{{ $question->id }}}" type="button"><i class="fa fa-fw fa-lg fa-arrow-up"></i></button>
                                @endif
                                @if($i + 1 < count($questions))
                                <button class="btn btn-default move-down-btn" data-question="{{{ $question->id }}}" type="button"><i class="fa fa-fw fa-lg fa-arrow-down"></i></button>
                                @endif
                                <button class="btn btn-danger remove-btn" data-question="{{{ $question->id }}}" type="button"><i class="fa fa-fw fa-lg fa-trash-o"></i></button>
                                @if($question->required)
                                <button class="btn btn-info required-btn" data-question="{{{ $question->id }}}" type="button">Optional</button>
                                @else
                                <button class="btn btn-info required-btn" data-question="{{{ $question->id }}}" type="button">Require</button>
                                @endif
                            </div>
                        </p>
                    </div>
                </div>
            </div>

            <hr/>
            @endforeach
        </ol>
    </div>
</div>
<div class="modal fade" id="add-question" tabindex="-1" role="dialog" aria-labelledby="addLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Add a Question</h4>
            </div>
            <div class="modal-body">
                <div class="list-group">
                @foreach($allQuestions as $question)
                    <a class="list-group-item add-question-item" data-question="{{{ $question->id }}}">
                        <p>
                            {{ Form::label($question->title) }}
                            @if ($question->type == 'text')
                            {{ Form::text($question->name, null, ['class' => 'form-control disabled']) }}
                            @elseif ($question->type == 'boolean')
                            <div class="checkbox">
                                {{ Form::radio($question->name, 0, false) }} No
                                {{ Form::radio($question->name, 1, false) }} Yes
                            </div>
                            @elseif ($question->type == 'multiple')
                            @foreach(json_decode($question->answers, true) as $value => $answer)
                            <div class="checkbox">
                                {{ Form::radio($question->name, $value, null) }} {{ $answer }}
                            </div>
                            @endforeach
                            @elseif ($question->type == 'textarea')
                            {{ Form::textarea($question->name, null, ['class' => 'form-control']) }}
                            @endif
                            <span class="help-block">{{ (isset($question->required)) ? 'Optional' : '' }}</span>
                        </p>
                    </a>
                @endforeach
                </div>
                <p>Selected item will be added at the end of the current questions</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button id="add-form-submit" type="button" class="btn btn-primary">Add Selected Question</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
<script>
var selectedQuestion = null;
$('.remove-btn').on('click touchstart', function (e) {
    e.preventDefault();

    if (confirm('Are you sure?')) {
        var question = $(this).data('question');
        $.ajax({
            url: '{{ route('league_edit', [$league->slug, 'registration_questions']) }}',
            type: 'post',
            data: { _token: '{{{ csrf_token() }}}', question: question, type: 'remove'},
            success: function (resp) {
                if (resp.status == 'success') {
                    window.location.reload();
                }
            }
        });
    }
});

['move-up', 'move-down', 'required'].map(function (item) {
    $('.' + item + '-btn').on('click touchstart', function (e) {
        e.preventDefault();
        var question = $(this).data('question');
        $.ajax({
            url: '{{ route('league_edit', [$league->slug, 'registration_questions']) }}',
            type: 'post',
            data: { _token: '{{{ csrf_token() }}}', question: question, type: item},
            success: function (resp) {
                if (resp.status == 'success') {
                    window.location.reload();
                }
            }
        });
    });
});
$('#add-form-submit').hide();

$('.add-question-item').on('click touchstart', function (e) {
    e.preventDefault();

    if ($(this).hasClass('active')) {
        $('.add-question-item').show('fast');
        $(this).removeClass('active');
        selectedQuestion = null;
    } else {
        selectedQuestion = $(this).data('question');
        $('.add-question-item').hide('fast');
        $(this).show('fast');
        $(this).addClass('active');
    }

    if (selectedQuestion !== null) {
        $('#add-form-submit').fadeIn('fast');
    } else {
        $('#add-form-submit').fadeOut('fast');
    }
});

$('#add-form-submit').on('click touchstart', function (e) {
    e.preventDefault();
    var selectedQuestion = $('.add-question-item.active').data('question');
    $.ajax({
        url: '{{ route('league_edit', [$league->slug, 'registration_questions']) }}',
        type: 'post',
        data: { _token: '{{{ csrf_token() }}}', question: selectedQuestion, type: 'add-question'},
        success: function (resp) {
            if (resp.status == 'success') {
                window.location.reload();
            }
        }
    });
});
</script>
@endsection
