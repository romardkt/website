@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Update Youth Coaching Page</h2>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-xs-12">
        @include('layouts.partials.errors')

        {{ Form::open(['class' => 'form form-vertical']) }}
            @include('layouts.partials.page', ['showDisplay' => false])
        {{ Form::close() }}
</div>
@endsection

@section('page-scripts')
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
@endsection
