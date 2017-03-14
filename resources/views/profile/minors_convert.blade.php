@section('page-scripts')
@endsection

@section('profile_content')
<div class="row">
    <div class="col-xs-12">
        <h1>Convert account to normal account</h1>

        <p>
            To convert a minor account you must enter the email address to use
            for the regular account.  We will then send an password reset link
            to that email.  This will allow the new account to specify a password
            for logging in.  All of the leagues and information will be moved
            with the new account.
        </p>

        <hr/>

        @include('partials.errors')

        {!! Form::open(['class' => 'form form-vertical', 'role' => 'form']) !!}
        <div class="form-group">
            {!! Form::label('Email address') !!}
            {!! Form::email('email', null, ['placeholder' => 'Email address', 'class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Password') !!}
            {!! Form::password('password', ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Confirm Password') !!}
            {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">Convert Account</button>
                <a class="btn btn-default" href="{{ route('profile_minor_edit', $minor->id) }}">Cancel</a>
            </div>
        </div>
        {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

@include('profile.header')
