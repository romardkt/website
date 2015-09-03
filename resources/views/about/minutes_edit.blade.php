@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Update Meeting Minutes</h2>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        {{ Form::model($minute, ['class' => 'form form-vertical', 'files' => true]) }}
            @include('about.partials.minutes', ['buttonText' => 'Update Minutes'])
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
                $('#location_id').append('<option value="' + resp.value + '">' + resp.name + '</option>');
                $('#location_id').select2('val', resp.value);
                $('#addLocation').modal('hide');
            }
        }
    });
});
</script>
@endsection
