@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-8 col-sm-offset-2">
        <h2 class="text-center">User Account Registration</h2>
        <hr/>

        <h4>Players YOUNGER than 18 years of age</h4>
        <p>
            A CUPA player account is limited to <strong>18 years of age or older</strong>.  Please have your parents
            sign up with their information.  They will then be able to add you as a minor to their
            account and then register you for the league or activity you would like to participate
            in.  Please see the help to see how you would
            <a rel="noopener noreferrer" target="_blank" href="/upload/youth_registration.pdf">add a minor</a>.
        </p>

        <br/><br/>

        <h4>Players 18 years of age or OLDER</h4>
        <p>
            To create a CUPA player account you will have to enter in all the information below.
            After you create your account we will need to confirm your email address to verify it is a
            valid email address. <strong>We do this by sending an activation link to the email you specify</strong>.
            Once you click on that link you will be able to login to the system. If you do not receive
            that link or you ignore it your account will not be activated and you will not be able to
            login. Please check your spam folders and wait a few minutes before contacting us. If you
            still are not receiving the email please use the contact form or send an email to
            {!! secureEmail( 'webmaster@cincyultimate.org', 'webmaster@cincyultimate.org', '[CUPA] Player account activation help') !!}
        </p>

        <br/><br/>
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

        <div class="alert alert-info">
            <strong>Note:</strong> If you are registering for a minor please follow
            <a href="/upload/youth_registration.pdf">these instructions</a>.
        </div>

        <div class="form-group">
            {!! Form::label('Birthday') !!}
            {!! Form::input('date', 'birthday', null, ['class' => 'form-control datepicker text-center']) !!}
            <span class="help-text">You must be 18 or older to register</span>
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
            <div class="checkbox indent-more">
                {!! Form::checkbox('email_list', 1, true) !!} <label>Sign up for CUPA Anouncement List</label>
            </div>
        </div>

        <div class="form-group">
            <div class="checkbox indent-more">
                {!! Form::checkbox('volunteer_list', 1, true) !!} <label>Sign up for CUPA Volunteer List</label>
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
