@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">{{ $page->display }} Submission</h2>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8 text-justify">
        <p>
            Please fill out this form to apply for the {{ $page->display }}.
        </p>
        <hr/>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8">
        @include('layouts.partials.errors')

        {{ Form::model($submission, ['class' => 'form-vertical', 'role' => 'form']) }}

        <div class="form-group">
            {{ Form::label('Name') }}
            {{ Form::text('name', null, ['class' => 'form-control', 'disabled']) }}
            <span class="help-block">Enter your name</span>
        </div>

        <div class="form-group">
            {{ Form::label('Email Address') }}
            {{ Form::text('email', null, ['class' => 'form-control', 'disabled']) }}
            <span class="help-block">Make sure this is correct so we may contact you</span>
        </div>

        <div class="form-group">
            {{ Form::label('Accept Scholarship') }}
            <div class="checkbox">
                {{ Form::checkbox('accepted', null) }} Mark scholarship as accepted?
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('Comments?') }}
            {{ Form::textarea('comments', null, ['class' => 'form-control']) }}
            <span class="help-block">(Optional) Enter any comments</span>
        </div>

        <hr/>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">Update Request</button>
                <a class="btn btn-default" href="{{ route('scholarship_hoy_manage') }}">Cancel</a>
            </div>
        </div>

        {{ Form::close() }}

    </div>
</div>
@endsection
