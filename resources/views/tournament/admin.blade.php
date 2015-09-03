@extends('layouts.tournament')

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @include('layouts.partials.errors')

        {{ Form::model($tournament, ['class' => 'form form-vertical', 'role' => 'form', 'files' => true]) }}

        <legend>Update Tournament Information</legend>

        <div class="form-group">
            {{ Form::label('Display Name') }}
            {{ Form::text('display_name', null, ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('Override Email Address') }}
            {{ Form::email('override_email', null, ['class' => 'form-control']) }}
            <span class="help-block">Single email contact, will override individual emails (Optional)</span>
        </div>

        @if($tournament->image !== '/data/tournaments/default.jpg')
        <div class="current-picture">
            <div class="text-muted">Current Image</div>
            <img src="{{ asset($tournament->image) }}"/>
        </div>
        <div class="form-group">
            <div class="checkbox">
                {{ Form::checkbox('header_remove') }} Remove Image
            </div>
        </div>
        @endif

        <div class="form-group">
            {{ Form::label('Header Image') }}
            {{ Form::file('header', null, ['class' => 'form-control']) }}
            <span class="help-block">This will replace the current one</span>
        </div>

        <div class="form-group">
            <div class="checkbox">
                {{ Form::checkbox('use_bid', 1, false) }} Use the online bid system?
            </div>
        </div>

        <div class="form-group">
            <div class="checkbox">
                {{ Form::checkbox('is_visible', 1, false) }} Tournament page visible?
            </div>
        </div>

        <div class="form-group">
            <div class="checkbox">
                {{ Form::checkbox('has_teams', 1, false) }} Tournament has team management?
            </div>
        </div>

        <legend>Tournament Divisions</legend>

        <div class="form-group">
            <?php
                $selectedDivisions = [];
                $divisions = json_decode($tournament->divisions);
                foreach ($divisions as $division) {
                    $selectedDivisions[] = $division;
                }
            ?>
            {{ Form::label('Divisions') }}
            {{ Form::select('divisions[]', array_combine(Config::get('cupa.divisions'),Config::get('cupa.divisions')), $selectedDivisions, ['class' => 'form-control select2', 'multiple']) }}
        </div>

        <legend>Tournament Dates</legend>

        <div class="form-group">
            <div class="checkbox">
                {{ Form::checkbox('tenative_date', 1, false) }} Dates Tenative?
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('Start Date') }}
            {{ Form::text('start', convertDate($tournament->start, 'm/d/Y'), ['class' => 'datepicker text-center form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('End Date') }}
            {{ Form::text('end', convertDate($tournament->end, 'm/d/Y'), ['class' => 'datepicker text-center form-control']) }}
        </div>

        <hr/>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">Update</button>
                <a class="btn btn-default" href="{{ route('tournament', [$tournament->name, $tournament->year]) }}">Cancel</a>
            </div>
        </div>

        {{ Form::close() }}
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
$('.select2').select2({
    placeholder: 'Select a division'
});
</script>
@endsection
