@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Add a Pickup</h2>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @include('layouts.partials.errors')

        {{ Form::open(['class' => 'form form-vertical', 'role' => 'form']) }}
            @include('around.partials.pickup', ['buttonText' => 'Create Pickup'])
        {{ Form::close() }}

        @include('layouts.partials.location')
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

handleSelect2('#contacts', '{{ route('typeahead_users') }}', 15);
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
