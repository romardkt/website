@extends('layouts.tournament')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Edit Tournament Location</h2>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-xs-12 col-sm-offset-2 col-sm-8">
        @include('layouts.partials.errors')

        {{ Form::model($tournament, ['class' => 'form form-vertical', 'role' => 'form']) }}

        <div class="form-group">
            {{ Form::label('Location') }}
            {{ Form::select('location_id', $locations, null, ['class' => 'form-control select2']); }}
            <span class="help-block">If location does not exist you may create one below</span>
            <p>&nbsp; <button data-toggle="modal" data-target="#addLocation" type="button" class="btn btn-primary">Add a Location</button></p>
        </div>

        <hr/>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">Update Location</button>
                <a class="btn btn-default" href="{{ route('tournament_location', [$tournament->name, $tournament->year]) }}">Cancel</a>
            </div>
        </div>

        {{ Form::close() }}

        @include('layouts.partials.location')
    </div>
</div>
@endsection

@section('page-scripts')
<script>
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
                $('#location_id').append('<option value="' + resp.value + '">' + resp.name + '</option>');
                $('#location_id').select2('val', resp.value);
                $('#addLocation').modal('hide');
            }
        }
    });
});
</script>
@endsection
