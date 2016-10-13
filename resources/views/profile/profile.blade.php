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
</script>
@endsection

@section('profile_content')
{!! Form::model($data, ['class' => 'form form-vertical', 'role' => 'form', 'files' => true]) !!}

<legend>Personal Information</legend>

<div class="form-group">
    {!! Form::label('Email Address') !!}
    {!! Form::email('email', null, ['class' => 'form-control']) !!}
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
    {!! Form::text('birthday', null, ['class' => 'form-control datepicker text-center']) !!}
</div>

<div class="form-group">
    {!! Form::label('Gender') !!}
    <div class="checkbox">
        {!! Form::radio('gender', 'Male', null, ['id' => 'gender-male']) !!} &nbsp;{!! Form::label('gender-male', 'Male') !!}
        &nbsp;&nbsp;&nbsp;&nbsp;
        {!! Form::radio('gender', 'Female', null, ['id' => 'gender-female']) !!} &nbsp;{!! Form::label('gender-female', 'Female') !!}
    </div>
</div>

<br/>

<legend>Other Information</legend>

<div class="current-picture">
    <div class="text-muted">Current Image</div>
    <img src="{{ asset($user->avatar) }}"/>
</div>
@if ($user->avatar != '/data/users/default.png')
<div class="form-group">
    <div class="checkbox">
        {!! Form::checkbox('avatar_remove', 1, false) !!} Remove Image
    </div>
</div>
@endif

<div class="form-group">
    {!! Form::label('Avatar') !!}
    {!! Form::file('avatar', null, ['class' => 'form-control']) !!}
    <span class="help-block">This will replace the current image</span>
</div>

<div class="form-group">
    {!! Form::label('Phone Number') !!}
    {!! Form::text('phone', null, ['class' => 'form-control']) !!}
    <span class="help-block">Format: ###-###-####</span>
</div>

<div class="form-group">
    {!! Form::label('Nickname') !!}
    {!! Form::text('nickname', null, ['class' => 'form-control']) !!}
    <span class="help-block">(Optional) Leave blank for none</span>
</div>

<div class="form-group">
    {!! Form::label('Height') !!}
    {!! Form::text('height', null, ['class' => 'form-control']) !!}
    <span class="help-block">Height in inches</span>
</div>

<div class="form-group">
    {!! Form::label('Level') !!}
    {!! Form::select('level', array_combine(Config::get('cupa.levels'), Config::get('cupa.levels')), null, ['class' => 'form-control']) !!}
    <span class="help-block">Select the highest level you have played</span>
</div>

<div class="form-group">
    {!! Form::label('Experience') !!}
    {!! Form::text('experience', null, ['class' => 'form-control']) !!}
    <span class="help-block">Enter the year you started playing</span>
</div>

<hr/>

<div class="form-group">
    <div class="col-xs-12 text-center">
        <button type="submit" class="btn btn-primary">Update Information</button>
    </div>
</div>

{!! Form::close() !!}
@endsection

@include('profile.header')
