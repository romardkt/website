@extends('app')

@section('content')
@include('layouts.page_header')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Become a Volunteer</h2>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @if($isVolunteer)
        <div class="row">
            <div class="alert alert-warning col-xs-12 col-sm-10 col-sm-offset-1">
                <h4>You have already signed up to become a Volunteer</h4>
                <p>You may update your information below if anything changed</p>
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                @include('layouts.partials.errors')

                {{ Form::model($user, ['class' => 'form form-vertical', 'role' => 'form']) }}
                @if ($isVolunteer || $isAuthorized['user'])
                <legend>Update Registration</legend>
                <div class="form-group">
                    {{ Form::label('Email Address') }}
                    {{ Form::email('email', null, ['class' => 'form-control', 'disabled']) }}
                </div>

                <div class="form-group">
                    {{ Form::label('First Name') }}
                    {{ Form::text('first_name', null, ['class' => 'form-control']) }}
                </div>

                <div class="form-group">
                    {{ Form::label('Last Name') }}
                    {{ Form::text('last_name', null, ['class' => 'form-control']) }}
                </div>

                <div class="form-group">
                    {{ Form::label('Phone') }}
                    {{ Form::text('phone', $user->profile->phone, ['class' => 'form-control']) }}
                </div>

                <div class="form-group">
                    {{ Form::label('Birthday') }}
                    {{ Form::input('date', 'birthday', convertDate($user->birthday, 'm/d/Y'), ['class' => 'form-control datepicker text-center']) }}
                </div>

                <div class="form-group">
                    {{ Form::label('Gender') }}
                    <div class="checkbox">
                        {{ Form::radio('gender', 'Male', null, ['id' => 'gender-male']) }} &nbsp;{{ Form::label('gender-male', 'Male') }}
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        {{ Form::radio('gender', 'Female', null, ['id' => 'gender-female']) }} &nbsp;{{ Form::label('gender-female', 'Female') }}
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::label('Years Involved with CUPA') }}
                    {{ Form::select('involvement', $volunteerChoices['involvement'], (isset($user->volunteer)) ? $user->volunteer->involvement : null, ['class' => 'form-control']) }}
                </div>

                <div class="form-group">
                    {{ Form::label('Primary Interest(s) for Volunteering') }}
                    {{ Form::select('primary_interest[]', $volunteerChoices['primary_interest'], (isset($user->volunteer)) ? explode(', ', $user->volunteer->primary_interest) : null, ['class' => 'form-control select2', 'multiple']) }}
                    <span class="help-block">Select all that you are interested in</span>
                </div>

                <div class="form-group">
                    {{ Form::label('Other: Please Specify only if you selected other') }}
                    {{ Form::textarea('other', (isset($user->volunteer)) ? $user->volunteer->other : null, ['class' => 'form-control', 'rows' => 4]) }}
                </div>

                <div class="form-group">
                    {{ Form::label('Please list all past CUPA volunteer experience') }}
                    {{ Form::textarea('experience', (isset($user->volunteer)) ? $user->volunteer->experience : null, ['class' => 'form-control', 'rows' => 4]) }}
                </div>

                @else
                <div class="text-center">
                <p class="text-justify">To become a volunteer we require some information from you about yourself so that we may contact you for opportunities.  We will use this information when you register for an event.  If you already have an account you may login and if you do not have an account  you may sign-up for one.</p>

                <p>If you <strong>have</strong> an account you may login to your account to continue in the upper right.</p>
                <p class="text-muted">- OR -</p>
                <p>If you <strong>do not</strong> already have an account, you may create a new account <a href="{{ route('register') }}">here</a></p>
                </div>
                @endif
                @if($isAuthorized['user'])
                <hr/>
                <div class="form-group">
                    <div class="col-xs-12 text-center">
                        @if($isVolunteer)
                        <button type="submit" class="btn btn-primary">Update Information</button>
                        @else
                        <button type="submit" class="btn btn-primary">Become a Volunteer</button>
                        @endif
                    </div>
                </div>
                {{ Form::close() }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
<script>
$('.datepicker').pickadate({
    format: 'mm/dd/yyyy',
    editable: false,
    selectYears: true,
    selectMonths: true
});

$('.select2').select2({
    placeholder: 'Select an answer'
});
</script>
@endsection
