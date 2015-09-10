@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Add a Team</h2>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @include('partials.errors')

        {!! Form::open(['class' => 'form form-vertical', 'role' => 'form', 'files' => true]) !!}
            @include('teams.partials.team', ['submitText' => 'Create Team'])
        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('page-scripts')
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script>
handleSelect2('#captains', '{{ route('typeahead_users') }}', 15);
$('.select2').select2({
    placeholder: 'Select a division'
});
</script>
@endsection
