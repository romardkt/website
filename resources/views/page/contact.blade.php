@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Contact Us</h2>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @include('partials.errors')

        {!! Form::open(['class' => 'form form-vertical', 'role' => 'form', 'files' => true]) !!}

        <div class="form-group">
            {!! Form::label('Your Name') !!}
            {!! Form::text('from_name', (Auth::check()) ? Auth::user()->fullname() : null, ['class' => 'form-control ckeditor']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Your Email') !!}
            {!! Form::text('from_email', (Auth::check()) ? Auth::user()->email : null, ['class' => 'form-control ckeditor']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Subject') !!}
            {!! Form::text('subject', '[CUPA] More Information', ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Message') !!}
            {!! Form::textarea('message', null, ['class' => 'form-control ckeditor']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Verify you\'re human') !!}
            {!! Form::captcha() !!}
        </div>

        <div class="row">
            <div class="col-xs-12 text-center">
                <button class="btn btn-primary" type="submit">Send Message</button>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('page-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.6.2/ckeditor.js"></script>
@endsection
