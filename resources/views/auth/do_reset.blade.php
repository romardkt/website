@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-8 col-sm-offset-2 text-center">
        <h2>User Password Reset</h2>
        <p>
            To reset your password all you need to do is enter a new password two (2) times.
        </p>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-sm-offset-3">
        @include('partials.errors')

        {!! Form::open(['class' => 'form form-vertical', 'role' => 'form']) !!}

        <legend>Enter New Password</legend>

        <div class="form-group">
            {!! Form::label('Password') !!}
            {!! Form::password('password', ['placeholder' => 'Enter password', 'class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Confirm') !!}
            {!! Form::password('password_confirmation', ['placeholder' => 'Confirm password', 'class' => 'form-control']) !!}
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
@endsection
