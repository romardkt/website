@extends('app')

@section('content')
<div class="profile">
    <div class="row">
        <div class="col-xs-12 col-sm-offset-1 col-sm-4 text-center">
            <img class="avatar" src="{{ asset(Auth::user()->avatar) }}"/>
        </div>
        <div class="col-xs-12 col-sm-6 text-center">
            <h1>{{ Auth::user()->fullname() }}</h1>
            <h4 class="text-muted">{{ (Auth::user()->profile->nickname) ? 'aka \'' . Auth::user()->profile->nickname . '\'' : '' }}</h4>
            <h4>
                {{ displayHeight(Auth::user()->profile->height) }} tall, {{ displayAge(Auth::user()->birthday) }} years old, played for {{ displayExperience(Auth::user()->profile->experience) }}
                @if(Auth::user()->profile->level != 'New')
                    up to {{ displayLevel(Auth::user()->profile->level) }}
                @endif
            </h4>
            <hr/>
            <div class="status">
                @if(Auth::user()->hasWaiver())
                <span class="label label-success">Waiver Signed</span>
                @else
                    @if(Auth::user()->getAge() >= 18)
                    <span class="label label-danger"><a href="{{ route('waiver', [date('Y'), Auth::user()->id]) }}">Waiver NOT Signed</a></span>
                    @else
                    <span class="label label-danger">Waiver NOT Signed</span>
                    @endif
                @endif

                @if(Auth::user()->profileComplete())
                <span class="label label-success">Complete</span>
                @else
                <span class="label label-danger"><a href="{{ route('profile_leagues') }}">Profile Incomplete</a></span>
                @endif

                @if(Auth::user()->balance())
                <span class="label label-danger"><a href="{{ route('profile_leagues') }}">Overdue ${{ Auth::user()->balance() }}</a></span>
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
