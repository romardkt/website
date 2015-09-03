@extends('app')

@section('content')
<div class="profile">
    <div class="row">
        <div class="col-xs-12 col-sm-offset-1 col-sm-4 text-center">
            <img class="avatar" src="{{ asset($isAuthorized['userData']->avatar) }}"/>
        </div>
        <div class="col-xs-12 col-sm-6 text-center">
            <h1>{{{ $isAuthorized['userData']->fullname() }}}</h1>
            <h4 class="text-muted">{{{ ($isAuthorized['userData']->profile->nickname) ? 'aka \'' . $isAuthorized['userData']->profile->nickname . '\'' : '' }}}</h4>
            <h4>
                {{{ displayHeight($isAuthorized['userData']->profile->height) }}} tall, {{{ displayAge($isAuthorized['userData']->birthday) }}} years old, played for {{{ displayExperience($isAuthorized['userData']->profile->experience) }}}
                @if($isAuthorized['userData']->profile->level != 'New')
                    up to {{{ displayLevel($isAuthorized['userData']->profile->level) }}}
                @endif
            </h4>
            <hr/>
            <div class="status">
                @if($isAuthorized['userData']->hasWaiver())
                <span class="label label-success">Waiver Signed</span>
                @else
                    @if($isAuthorized['userData']->getAge() >= 18)
                    <span class="label label-danger"><a href="{{ route('waiver', [date('Y'), $isAuthorized['userData']->id]) }}">Waiver NOT Signed</a></span>
                    @else
                    <span class="label label-danger">Waiver NOT Signed</span>
                    @endif
                @endif

                @if($isAuthorized['userData']->profileComplete())
                <span class="label label-success">Complete</span>
                @else
                <span class="label label-danger"><a href="{{ route('profile_leagues') }}">Profile Incomplete</a></span>
                @endif

                @if(isset($isAuthorized['userData']->balance->balance))
                <span class="label label-danger"><a href="{{ route('profile_leagues') }}">Overdue ${{{ $isAuthorized['userData']->balance->balance }}}</a></span>
                @else
                <span class="label label-success">Overdue $0</span>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-offset-1 col-sm-10">
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-offset-1 col-sm-3 text-center">
            @include('profile.menu')
        </div>
        <div class="col-xs-12 col-sm-7">
            @yield('profile_content')
        </div>
    </div>
</div>
@endsection
