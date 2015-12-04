@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-8 col-sm-offset-2">
        <h2 class="text-center">User Password Reset</h2>
        <p>
            To reset your password just fill in your email address.  Once entered an email with a link to reset your password
            will be sent to you.  Just follow the directions in the email to reset your password.  You may {!! secureEmail( 'webmaster@cincyultimate.org', 'contact us', '[CUPA] User Account Password Reset') !!} if you are having problems or do not receive the password reset email.
        </p>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-sm-offset-3">
        @include('partials.errors')

        {!! Form::open(['class' => 'form form-vertical', 'role' => 'form']) !!}

        <legend>Enter Email to Reset Password</legend>

        <div class="form-group">
            {!! Form::label('Email to reset password') !!}
            {!! Form::email('email', null, ['class' => 'form-control']) !!}
            <span class="help-block">Enter in the email of the account you would like to reset</span>
        </div>

        <hr/>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">Reset My Password</button>
                <a class="btn btn-default" href="{{ route('home') }}">Cancel</a>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
</div>
<div class="row text-center">
    <div class="col-xs-12">

    </div>
</div>
@endsection
