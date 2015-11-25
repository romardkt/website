@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Edit {{ $league->displayName() }} Registration</h2>
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @include('partials.errors')

        {!! Form::open(['class' => 'form form-vertical', 'role'=> 'form']) !!}

        <legend>Registration</legend>

        <div class="form-group">
            {!! Form::label('Start Date') !!}
            {!! Form::text('start_date', date('m/d/Y', strtotime($league->registration->begin)), ['class' => 'datepicker text-center form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Start Time') !!}
            {!! Form::text('start_time', date('h:i A', strtotime($league->registration->begin)), ['class' => 'clockpicker text-center form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('End Date') !!}
            {!! Form::text('end_date', date('m/d/Y', strtotime($league->registration->end)), ['class' => 'datepicker text-center form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('End Time') !!}
            {!! Form::text('end_time', date('h:i A', strtotime($league->registration->end)), ['class' => 'clockpicker text-center form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Cost') !!}
            {!! Form::number('cost', $league->registration->cost, ['class' => 'text-center form-control']) !!}
            <span class="help-block">Leave blank for NO cost</span>
        </div>

        <legend>Registration Limits</legend>

        <div class="form-group">
            {!! Form::label('Male') !!}
            {!! Form::number('male', $league->limits->male, ['class' => 'text-center form-control']) !!}
            <span class="help-block">Leave blank for NO limit</span>
        </div>

        <div class="form-group">
            {!! Form::label('Female') !!}
            {!! Form::number('female', $league->limits->female, ['class' => 'text-center form-control']) !!}
            <span class="help-block">Leave blank for NO limit</span>
        </div>

        <div class="form-group">
            {!! Form::label('Total') !!}
            {!! Form::number('total', $league->limits->total, ['class' => 'text-center form-control']) !!}
            <span class="help-block">Leave blank for NO limit</span>
        </div>

        <div class="form-group">
            {!! Form::label('teams') !!}
            {!! Form::number('teams', $league->limits->teams, ['class' => 'text-center form-control']) !!}
            <span class="help-block">Leave blank for NO limit</span>
        </div>


        <legend>Registration Questions</legend>

        <a class="btn btn-primary" href="{{ route('league_edit', [$league->slug, 'registration_questions']) }}">Update Registration Questions</a>

        <hr/>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">Update Registration</button>
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
$('.clockpicker').clockpicker({
    donetext: 'Done',
    twelvehour: true,
    align: 'right',
});
</script>
@endsection
