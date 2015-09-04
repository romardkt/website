@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Add Meeting Minutes</h2>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        {!! Form::open(['class' => 'form form-vertical', 'files' => true]) !!}
            @include('about.partials.minutes', ['buttonText' => 'Add Minutes'])
        {!! Form::close() !!}

        @include('partials.location')
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

</script>
@endsection
