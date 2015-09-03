@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Update Youth Clinic</h2>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-xs-12">
        @include('layouts.partials.errors')

        {{ Form::model($clinic, ['class' => 'form form-vertical', 'role' => 'form']) }}
            @include('youth.partials.clinic', ['type' => 'Update'])
        {{ Form::close() }}
    </div>
</div>

@endsection

@section('page-scripts')
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
@endsection
