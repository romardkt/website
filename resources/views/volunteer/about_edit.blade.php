@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Volunteer About Edit</h2>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-xs-12">
        {!! Form::open(['class' => 'form form-vertical']) !!}
            @include('partials.page', ['showDisplay' => false])
        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('page-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.6.2/ckeditor.js"></script>
@endsection
