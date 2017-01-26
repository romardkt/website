@extends('tournament')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Update a Team</h2>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @include('partials.errors')

        {!! Form::model($team, ['class' => 'form form-vertical', 'role' => 'form']) !!}
            @include('tournament.partials.team', ['type' => 'Update'])
        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('page-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.6.2/ckeditor.js"></script>
<script>
$('.select2').select2({
    placeholder: 'Select a division'
});
</script>
@endsection
