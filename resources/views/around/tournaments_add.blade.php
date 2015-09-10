@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Add a Tournament</h2>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        {!! Form::open(['class' => 'form form-vertical', 'role' => 'form']) !!}

        <legend>Information</legend>

        <div class="form-group">
            {!! Form::label('Previous Tournament') !!}
            {!! Form::select('name', array_merge([0 => 'New Tournament'], $tournamentList), null, ['class' => 'form-control', 'onchange' => 'updateView($(this).val());']) !!}
            <span class="help-block">Select a tournament name</span>
        </div>

        <div class="form-group">
            {!! Form::label('Name') !!}
            {!! Form::text('new_name', null, ['class' => 'form-control', 'id' => 'new_name']) !!}
            <span class="help-block">Enter tournament name (NOT THE DISPLAY NAME).  Will be part of the url</span>
        </div>

        <div class="form-group">
            {!! Form::label('Title') !!}
            {!! Form::text('display_name', null, ['class' => 'form-control']) !!}
            <span class="help-block">This is what you want displayed in the page header</span>
        </div>

        <div class="form-group">
            {!! Form::label('Year') !!}
            {!! Form::select('year', array_combine(range(date('Y') - 5, date('Y') + 5), range(date('Y') - 5, date('Y') + 5)), date('Y'), ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Directors') !!}
            {!! Form::hidden('directors', $initial, ['id' => 'directors']) !!}
            <span class="help-block">Start by typing a directors name</span>
        </div>

        <div class="form-group">
            {!! Form::label('Override Email') !!}
            {!! Form::text('email_override', null, ['class' => 'form-control']) !!}
            <span class="help-block">If entered, it will override directors email addresses (Optional)</span>
        </div>

        <div class="form-group">
            {!! Form::label('Divisions') !!}
            {!! Form::select('divisions[]', $divisions, null, ['class' => 'select2 form-control', 'multiple' => true]) !!}
            <span class="help-block">Select all divisions for the tournament</span>
        </div>

        <div class="form-group">
            {!! Form::label('Cost') !!}
            {!! Form::number('cost', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Bid System') !!}
            <div class="checkbox">
                {!! Form::checkbox('use_bid', 1, true) !!} Use Bid System?
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('CUPA paypal') !!}
            <div class="checkbox">
                {!! Form::checkbox('use_paypal', 1, true) !!} Use CUPA paypal payment?
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('Visibility') !!}
            <div class="checkbox">
                {!! Form::checkbox('is_visible', 1) !!} Should it be visible?
            </div>
        </div>

        <legend>Dates</legend>

        <div class="form-group">
            {!! Form::label('Tenative Dates') !!}
            <div class="checkbox">
                {!! Form::checkbox('tenative_date', 1) !!} Are the dates tenative?
                <span class="help-block">If you just know the month check this, but still pick a start and end date/time in the month.</span>
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('Start Date') !!}
            {!! Form::text('start_date', null, ['class' => 'datepicker text-center form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Start Time') !!}
            {!! Form::text('start_time', null, ['class' => 'clockpicker text-center form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('End Date') !!}
            {!! Form::text('end_date', null, ['class' => 'datepicker text-center form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('End Time') !!}
            {!! Form::text('end_time', null, ['class' => 'clockpicker text-center form-control']) !!}
        </div>

        <legend>Location</legend>

        <div class="form-group">
            {!! Form::label('Location') !!}
            {!! Form::select('location_id', $locations, null, ['class' => 'form-control select2']); !!}
            <span class="help-block">If location does not exist you may create one below</span>
            <p>&nbsp; <button data-toggle="modal" data-target="#addLocation" type="button" class="btn btn-primary">Add a Location</button></p>
        </div>

        <legend>Descriptions</legend>

        <div class="form-group">
            {!! Form::label('Description') !!}
            {!! Form::textarea('description', null, ['class' => 'form-control ckeditor']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Schedule') !!}
            {!! Form::textarea('schedule', null, ['class' => 'form-control ckeditor']) !!}
            <span class="help-block">(Optional)</span>
        </div>

        <hr/>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">Add Tournament</button>
                <a class="btn btn-default" href="{{ route('around_tournaments') }}">Cancel</a>
            </div>
        </div>

        {!! Form::close() !!}

        @include('partials.location')

    </div>
</div>
@endsection

@section('page-scripts')
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
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
handleSelect2('#directors', '{{ route('typeahead_users') }}', 5);
$('.select2').select2();

function updateView(tournamentNameId)
{
    if (tournamentNameId == 0) {
        $('#new_name').parents('.form-group').show();
    } else {
        $('#new_name').parents('.form-group').hide();
    }
}
</script>
@endsection
