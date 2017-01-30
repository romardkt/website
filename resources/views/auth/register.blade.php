@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-8 col-sm-offset-2">
        <h2 class="text-center">User Account Registration</h2>
        <p>
            To register for an account with the online CUPA system you will need to supply <strong>all of the information below</strong>.
            You will also have to be <strong>at least 18 years of age</strong>.  If you are younger than 13 years old and you are wanting
            to play in a youth league, please have your parents sign up and add you as a minor to their account.  They may
            then register as you for the league.
        </p>
        <p>
            After you submit the registration <strong>you will need to activate your account by confirming your email address</strong>.  We
            do this by sending an activation link to the email you specify.  Once you click on that link you will be able
            to login to the system.  <strong>If you do not receive that link or you ignore it your account will not be activated
            and you will not be able to login.</strong>  You may {!! secureEmail( 'webmaster@cincyultimate.org', 'contact us', '[CUPA] User Account Registration') !!} if you are having problems or do not receive the
            activation email.
        </p>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-sm-offset-3">
        @include('partials.errors')

        {!! Form::open(['class' => 'form form-vertical', 'role' => 'form']) !!}

        <legend>Registration</legend>

        <div class="form-group">
            {!! Form::label('Email Addresss') !!}
            {!! Form::email('email', null, ['class' => 'form-control']) !!}
            <span class="help-block">Make sure this email is valid as it will be used for activation</span>
        </div>

        <div class="form-group">
            {!! Form::label('First Name') !!}
            {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Last Name') !!}
            {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Birthday') !!}
            {!! Form::input('date', 'birthday', null, ['class' => 'form-control datepicker text-center']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Gender') !!}
            <div class="checkbox">
                {!! Form::radio('gender', 'Male', null, ['id' => 'gender-male']) !!} &nbsp;{!! Form::label('gender-male', 'Male') !!}
                &nbsp;&nbsp;&nbsp;&nbsp;
                {!! Form::radio('gender', 'Female', null, ['id' => 'gender-female']) !!} &nbsp;{!! Form::label('gender-female', 'Female') !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('Password') !!}
            {!! Form::password('password', ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Confirm Password') !!}
            {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
        </div>

        <hr/>

        <div class="form-group">
            <div class="checkbox">
                {!! Form::checkbox('email_list', 1, true) !!} Sign up for CUPA Anouncement List
            </div>
        </div>

        <div class="form-group">
            <div class="checkbox">
                {!! Form::checkbox('volunteer_list', 1, true) !!} Sign up for CUPA Volunteer List
            </div>
        </div>

        <hr/>

        <div class="form-group">
            {!! Form::label('Verify you\'re human') !!}
            {!! app('captcha')->display(); !!}
        </div>

        <hr/>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">Register Email Address</button>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('page-scripts')
<script>
$('.datepicker').pickadate({
    format: 'mm/dd/yyyy',
    editable: false,
    selectYears: 70,
    selectMonths: true,
    min: -25567,
    max: -6575
});

$('#reload-captcha').on('click touchstart', function (e) {
    var d = new Date();
    var newUrl = $('#field_captcha').attr('src');
    $('#field_captcha').attr('src', newUrl + '?' + d.getTime());
});
</script>
@endsection
