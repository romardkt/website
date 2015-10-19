@if(Session::has('owe'))
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <div class="alert alert-danger">
            <h4>You have an outstanding debt</h4>
            <p>Please pay up before registering.  Check your status <a href="{{ route('profile_leagues') }}">here</a></p>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @include('partials.errors')

        {!! Form::open(['class' => 'form form-vertical', 'role' => 'form']) !!}

        <legend>Select who to register as</legend>

        <div class="form-group">
            {!! Form::label('Available players') !!}
            @foreach(Cupa\User::fetchRegistrantsForRadio('user') as $name => $user)
            <div class="checkbox">
                {!! Form::radio('user', $user['value']) !!} {{ $name }}
            </div>
            @endforeach
        </div>

        <hr>

        <div class="alert alert-info">
            <p>If you have children you would like to register you can add them to your account in <a href="{{ route('profile_minors') }}">your profile</a>
        </div>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <a class="btn btn-default" href="{{ route('league', [$league->slug]) }}">Cancel</a>
                <button type="submit" class="btn btn-primary">Next <i class="fa fa-fw fa-lg fa-arrow-right"></i></button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
