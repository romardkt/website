@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Update Board Member</h2>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-xs-12">
        {{ Form::model($officer, ['class' => 'form form-vertical', 'role'=> 'form', 'files' => true]) }}
            @include('about.partials.board')
        {{ Form::close() }}
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

handleSelect2('#user_id', '{{ route('typeahead_users') }}', 1);
</script>
@endsection
