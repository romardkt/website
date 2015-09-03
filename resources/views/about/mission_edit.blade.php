@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Mission Statement</h2>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-xs-12">
        {{ Form::open(['class' => 'form form-vertical']) }}
            @include('layouts.partials.page', ['showDisplay' => false])
        {{ Form::close() }}
    </div>
</div>
@endsection

@section('page-scripts')
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
@endsection
