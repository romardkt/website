@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Update Youth Page</h2>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-xs-12">
        @include('partials.errors')

        {!! Form::open(['class' => 'form form-vertical']) !!}
            @include('partials.page', ['showDisplay' => false])
        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('page-scripts')
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
@endsection
