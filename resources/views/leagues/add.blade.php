@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Add a {{{ ucfirst($season) }}} League</h2>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @include('layouts.partials.errors')

        {{ Form::open(['class' => 'form form-vertical', 'role' => 'form']) }}

        <legend>League Infromation</legend>

        <div class="form-group">
            {{ Form::label('League Type') }}
            {{ Form::select('type', $types, null, ['class' => 'form-control']) }}
            <span class="help-block">Select the type of league</span>
        </div>

        <div class="form-group">
            {{ Form::label('Year') }}
            {{ Form::select('year', $years, null, ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('League Generation') }}
            <div class="checkbox">
                {{ Form::radio('league_type', 0, true, ['id' => 'new-league-radio']) }} {{ Form::label('new-league-radio', 'New League') }}
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                {{ Form::radio('league_type', 1, false, ['id' => 'copy-league-radio']) }} {{ Form::label('copy-league-radio', 'Copy From Previous') }}
            </div>
        </div>

        <div id="new-league">
            <div class="form-group">
                {{ Form::label('Season') }}
                {{ Form::select('season', $seasons, null, ['class' => 'form-control']) }}
                <span class="help-block">Select the season the league is played in</span>
            </div>

            <div class="form-group">
                {{ Form::label('Director(s)') }}
                {{ Form::hidden('directors', $initial, ['id' => 'directors']) }}
                <span class="help-block">Start by typing a directors name</span>
            </div>

            <div class="form-group">
                {{ Form::label('Day Played') }}
                {{ Form::select('day', $days, null, ['class' => 'form-control']) }}
                <span class="help-block">Select the day this league is played</span>
            </div>

            <div class="form-group">
                {{ Form::label('League Name') }}
                {{ Form::text('name', null, ['class' => 'form-control']) }}
                <span class="help-block">Enter a league name (Optional)</span>
            </div>

            <div class="form-group">
                {{ Form::label('Contact Email') }}
                {{ Form::email('override_email', null, ['class' => 'form-control']) }}
                <span class="help-block">Enter a SINGLE contact email for league (Optional)</span>
            </div>
        </div>

        <div id="copy-league">
            <div class="form-group">
                {{ Form::label('Copy From') }}
                {{ Form::select('copy', $prevLeagues, null, ['class' => 'form-control']) }}
                <span class="help-block">Select the type of league to copy from</span>
            </div>
        </div>

        <p class="alert alert-info">
            These are only the most basic of questions, you will have to create/update the information after the creation of the league.
        </p>

        <hr/>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">Create League</button>
                <a class="btn btn-default" href="{{ route('leagues', [$season]) }}">Cancel</a>
            </div>
        </div>

        {{ Form::close() }}
    </div>
</div>
<hr/>
@endsection

@section('page-scripts')
<script>
$('#copy-league').hide();
$('input[type=radio]').on('click touchstart', function (e) {
    if ($(this).val() == 0) {
        $('#copy-league').hide();
        $('#new-league').fadeIn('fast');
    } else {
        $('#new-league').hide();
        $('#copy-league').fadeIn('fast');
    }
});

if ($('#league_type').is(':checked')) {
    $('#copy-league').hide();
    $('#new-league').fadeIn('fast');
}

if ($('#league_type2').is(':checked')) {
    $('#new-league').hide();
    $('#copy-league').fadeIn('fast');
}

handleSelect2('#directors', '{{ route('typeahead_users') }}', 15);
</script>
@endsection
