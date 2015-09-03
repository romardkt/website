@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Edit {{{ $league->displayName() }}} Information</h2>
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @include('layouts.partials.errors')

        {{ Form::open(['class' => 'form form-vertical', 'role'=> 'form']) }}

        <legend>Information</legend>

        <div class="form-group">
            {{ Form::label('Director(s)') }}
            {{ Form::hidden('directors', $initial, ['id' => 'directors']) }}
            <span class="help-block">Start by typing a directors name</span>
        </div>

        @foreach(['league', 'draft', 'tournament'] as $locType)
        <legend>{{ ucfirst($locType)}} Information</legend>

        @if($locType != 'league')
        {{ Form::checkbox('is_' . $locType, 1, (isset($selectedLocations[$locType])) ? true : false, ['class' => 'location-check', 'id' => 'is-' . $locType, 'data-type' => $locType]) }} Check if there is a {{ $locType }}
        <br/><br/>
        @endif

        <div id="{{ $locType }}-view">
             <div class="form-group">
                {{ Form::label('Location') }}
                {{ Form::select($locType . '_location_id', $locations, (isset($selectedLocations[$locType])) ? $selectedLocations[$locType]->location_id : null, ['class' => 'form-control select2', 'id' => $locType . '_location_id']); }}
                <span class="help-block">If location does not exist you may create one below</span>
                <p>&nbsp; <button data-toggle="modal" data-target="#addLocation" type="button" class="btn btn-primary">Add a Location</button></p>
            </div>

            <div class="form-group">
                {{ Form::label('Start Date') }}
                {{ Form::text($locType . '_start_date', (isset($selectedLocations[$locType]->begin)) ? date('m/d/Y', strtotime($selectedLocations[$locType]->begin)) : null, ['class' => 'datepicker text-center form-control']) }}
            </div>

            <div class="form-group">
                {{ Form::label('Start Time') }}
                {{ Form::text($locType . '_start_time', (isset($selectedLocations[$locType]->begin)) ? date('h:i A', strtotime($selectedLocations[$locType]->begin)) : null, ['class' => 'clockpicker text-center form-control']) }}
            </div>

            <div class="form-group">
                {{ Form::label('End Date') }}
                {{ Form::text($locType . '_end_date', (isset($selectedLocations[$locType]->end)) ? date('m/d/Y', strtotime($selectedLocations[$locType]->end)) : null, ['class' => 'datepicker text-center form-control']) }}
            </div>

            <div class="form-group">
                {{ Form::label('End Time') }}
                {{ Form::text($locType . '_end_time', (isset($selectedLocations[$locType]->end)) ? date('h:i A', strtotime($selectedLocations[$locType]->end)) : null, ['class' => 'clockpicker text-center form-control']) }}
            </div>
        </div>
        @endforeach

        <hr/>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">Update Information</button>
                <a class="btn btn-default" href="{{ route('league', [$league->slug]) }}">Cancel</a>
            </div>
        </div>
        {{ Form::close() }}

        @include('layouts.partials.location')
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
$('.select2').select2({
    placeholder: 'Select a location'
});

$('#add-form-submit').on('click touchstart', function (e) {
    e.preventDefault();
    $.ajax({
        url: '{{ route('location_add') }}',
        type: 'post',
        data: $('#add-location-form').serialize(),
        success: function (resp) {
            if (resp.status != 'ok') {
                $('#location-error').html('<div class="alert alert-danger"><p>' + resp.message + '</p></div>');
            } else {
                $('#league_location_id').append('<option value="' + resp.value + '">' + resp.name + '</option>');
                $('#league_location_id').select2('val', resp.value);

                $('#draft_location_id').append('<option value="' + resp.value + '">' + resp.name + '</option>');
                $('#draft_location_id').select2('val', resp.value);

                $('#tournament_location_id').append('<option value="' + resp.value + '">' + resp.name + '</option>');
                $('#tournament_location_id').select2('val', resp.value);

                $('#addLocation').modal('hide');
            }
        }
    });
});

handleSelect2('#directors', '{{ route('typeahead_users') }}', 15);

if (!$('#is-draft').prop('checked')) {
    $('#draft-view').hide();
}
if (!$('#is-tournament').is(':checked')) {
    $('#tournament-view').hide();
}

$('.location-check').on('change', function (e) {
    var type = $(this).data('type');
    if (!this.checked) {
        if (confirm('This will clear the data if any.  Are you sure?')) {
            $('#' + type + '-view').fadeOut('fast');
            $('#' + type + '-view input').each(function (i, item) {
                $(item).val('');
            });
            $('#' + type + '-view select').val(0);
        } else {
            this.checked = !this.checked;
        }
    } else {
        $('#' + type + '-view').fadeIn('fast');
    }
});
</script>
@endsection
