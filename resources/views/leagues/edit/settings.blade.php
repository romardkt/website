@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Edit {{ $league->displayName() }} Settings</h2>
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @include('partials.errors')

        {!! Form::model($league, ['class' => 'form form-vertical', 'role'=> 'form']) !!}

        <div class="form-group">
            {!! Form::label('Date Visible') !!}
            {!! Form::text('date_visible', null, ['class' => 'datepicker text-center form-control']) !!}
            <span class="help-block">Select the date league should be visible</span>
        </div>

        <div class="form-group">
            {!! Form::label('League Name') !!}
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
            <span class="help-block">Enter league name (Optional)</span>
        </div>

        <div class="form-group">
            {!! Form::label('League Season') !!}
            {!! Form::select('season', $seasons, null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('League Day') !!}
            {!! Form::select('day', $days, null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Override Email') !!}
            {!! Form::email('override_email', null, ['class' => 'form-control']) !!}
            <span class="help-block">Enter an email to override the directors emails (Optional)</span>
        </div>

        <div class="form-group">
            {!! Form::checkbox('user_teams', 1, null) !!} Do you want the registrant to pick a team?
        </div>

        <div class="form-group">
            {!! Form::checkbox('has_pods', 1, null) !!} Does this league use pods instead of teams?
        </div>

        <div class="form-group">
            {!! Form::checkbox('is_youth', 1, null) !!} Is this a youth league?
        </div>

        <div class="form-group">
            {!! Form::checkbox('has_registration', 1, null) !!} Has registration of players?
        </div>

        <div class="form-group">
            {!! Form::checkbox('has_waitlist', 1, null) !!} Has a waitlist?
        </div>

        <div class="form-group">
            {!! Form::checkbox('default_waitlist', 1, null) !!} Players are placed in waitlist until paid
        </div>

        <hr/>
        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">Update Settings</button>
                <a class="btn btn-default" href="{{ route('league', [$league->slug]) }}">Cancel</a>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('page-scripts')
<script>
$('.datepicker').pickadate({
    format: 'mm/dd/yyyy',
    editable: false,
    selectYears: true,
    selectMonths: true
});
</script>
@endsection
