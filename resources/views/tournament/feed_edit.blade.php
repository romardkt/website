@extends('tournament')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Edit a News Item</h2>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @include('partials.errors')

        {!! Form::model($feed, ['class' => 'form form-vertical', 'role' => 'form']) !!}
            @include('tournament.partials.feed', ['submitText' => 'Update News Item'])
        {!! Form::close() !!}
   </div>
</div>
@endsection

@section('page-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.6.2/ckeditor.js"></script>
@endsection
