@section('profile_content')
@include('layouts.partials.errors')

{{ Form::open(['class' => 'form form-vertical', 'role' => 'form']) }}

<legend>Change Your Password</legend>

<div class="form-group">
    {{ Form::label('Password') }}
    {{ Form::password('password', ['class' => 'form-control']) }}
</div>

<div class="form-group">
    {{ Form::label('Confirm Password') }}
    {{ Form::password('password_confirmation', ['class' => 'form-control']) }}
</div>

<hr/>

<div class="form-group">
    <div class="col-xs-12 text-center">
        <button type="submit" class="btn btn-primary">Update Password</button>
    </div>
</div>

{{ Form::close() }}
@endsection

@include('profile.header')
