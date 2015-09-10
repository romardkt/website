@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Update Pickup Game</h2>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @include('partials.errors')

        {!! Form::model($pickup, ['class' => 'form form-vertical', 'role' => 'form']) !!}
            @include('around.partials.pickup', ['buttonText' => 'Update Pickup'])
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

handleSelect2('#contacts', '{{ route('typeahead_users') }}', 15);
$('.select2').select2({
    placeholder: 'Select a location'
});
</script>
@endsection
