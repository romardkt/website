@extends('app')

@section('content')
@include('page_header')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">{{ $league->displayName() }}</h2>
    </div>
</div>
@if($league->is_archived)
<div class="row">
    <div class="col-xs-12 text-center">
        <div class="alert alert-danger">
            <h4>League is archived</h4>
            <p>The league is only visible to those who can edit it.</p>
        </div>
    </div>
</div>
@elseif($league->date_visible === null || $league->date_visible >= (new DateTime())->format('Y-m-d H:i:s'))
<div class="row">
    <div class="col-xs-12 text-center">
        <div class="alert alert-warning">
            <h4>League NOT visible to everone</h4>
            <p>The league is only visible to those who can edit it.  When ready you can mark it as visible.</p>
        </div>
    </div>
</div>
@endif
<div class="row">
    <div class="col-xs-6 text-left">
        @if($league->is_youth)
        <a class="btn btn-default" href="{{ route('youth_leagues') }}"><i class="fa fa-lg fa-fw fa-chevron-circle-left"></i> All Leagues</a>
        @else
        <a class="btn btn-default" href="{{ route('leagues_' . $league->season) }}"><i class="fa fa-lg fa-fw fa-chevron-circle-left"></i> All Leagues</a>
        @endif
    </div>
    <div class="col-xs-6 text-right">
        @can('is-manager')
        <a class="btn btn-primary" href="{{ route('league_edit', [$league->slug, 'settings']) }}"><i class="fa fa-lg fa-fw fa-edit"></i> Settings</a>
            @if($league->is_archived == 0)
        <a class="btn btn-danger" onclick="return confirm('This will hide the league from the list, are you sure?');" href="{{ route('league_archive', [$league->slug]) }}"><i class="fa fa-lg fa-fw fa-lock"></i> Archive League</a>
            @else
        <a class="btn btn-success" href="{{ route('league_archive', [$league->slug]) }}"><i class="fa fa-lg fa-fw fa-unlock"></i> Un-Archive League</a>
            @endif
        @endif
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <h3 class="text-center">Description</h3>
        @can('is-manager')
        <div class="pull-right page-action">
            <a class="btn btn-primary" href="{{ route('league_edit', [$league->slug, 'description']) }}"><i class="fa fa-fw fa-lg fa-edit"></i> Edit</a>
        </div>
        @endif
        {!! $league->description !!}
        <hr/>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <h3 class="text-center">Information</h3>
        @can('is-manager')
        <div class="pull-right page-action">
            <a class="btn btn-primary" href="{{ route('league_edit', [$league->slug, 'information']) }}"><i class="fa fa-fw fa-lg fa-edit"></i> Edit</a>
        </div>
        @endif
        <div class="text-center">
            <div class="btn-group">
                @foreach(Config::get('cupa.league_menu') as $route => $data)
                @if($data['auth'] === null || Gate::allows('coach', $league) && ($data['auth'] == 'coach' && $league->is_youth == 1))
                <a class="btn btn-default{{ (Route::currentRouteName() == $route) ? ' active' : '' }}" href="{{ route($route, [$league->slug]) }}">{{ $data['name'] }}</a>
                @endif
                @endforeach
            </div>
        </div>
        <br/>
        <dl class="dl-horizontal">
            <dt>Director(s):</dt>
            <dd>
                @foreach($league->directors() as $director)
                @if ($league->override_email === null) {!! secureEmail($director->user->email, $director->user->fullname()) !!}
                @else {!! secureEmail($league->override_email, $director->user->fullname()) !!}
                @endif
                @endforeach
            </dd>
            @foreach($league->locations as $location)
            <dt>{{ ucfirst($location->type) }}:</dt>
            <dd>
                <p>
                {{ (new DateTime($location->begin))->format('M j Y')}} -
                {{ (new DateTime($location->end))->format('M j Y')}}

                {{ (new DateTime($location->begin))->format('h:i')}} <em>to</em>
                {{ (new DateTime($location->end))->format('h:i A')}}
                </p>
                <p>
                <a target="_blank" href="{{ $location->location->getUrl() }}">{{ $location->location->name }}</a><br/>
                {{ $location->location->street }}<br/>
                {{ $location->location->city . ', ' . $location->location->state . ' ' . $location->location->zip }}
                </p>
            </dd>
            @endforeach
        </dl>
        <hr/>
    </div>
</div>
@if($league->has_registration == 1)
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <h3 class="text-center">Registration</h3>
        @can('is-manager')
        <div class="pull-right page-action">
            <a class="btn btn-primary" href="{{ route('league_edit', [$league->slug, 'registration']) }}"><i class="fa fa-fw fa-lg fa-edit"></i> Edit</a>
        </div>
        @endif

        <dl class="dl-horizontal">
            <dt class="reg-status">Status:</dt>
            <dd class="reg-status">
                <strong class="{{ array_keys($registration['status'])[0] }}">
                    {{ $registration['status'][array_keys($registration['status'])[0]] }}
                </strong>
                @if(array_keys($registration['status'])[0] == 'text-success')
                @if($isAuthorized['user'])
                <p>
                    <a class="btn btn-primary" href="{{ route('league_register', [$league->slug]) }}">Register for League</a>
                </p>
                @else
                ( <a href="" data-toggle="modal" data-target="#login" title="Login">Please login to register</a> )
                @endif
                @endif
            </dd>
            <dt>When:</dt>
            <dd>
                {{ (new DateTime($league->registration->begin))->format('M j Y \a\t h:i A')}} <em>to</em>
                {{ (new DateTime($league->registration->end))->format('M j Y \a\t h:i A')}}
            </dd>
            <dt>Cost:</dt>
            <dd>
                <span class="text-warning">{{ ($league->registration->cost > 0) ? '$' . $league->registration->cost: 'Free' }}</span>
            </dd>
            <dt>Waiver:</dt>
            <dd>
                <p>All players must have a waiver on file with CUPA from a previous {{ $league->year }} league or present a printed and signed copy before play begins.</p>
                @if(Auth::check())
                <p>You may check your status <a href="{{ route('league_success', [$league->slug]) }}">here</a>.</p>
                @endif
            </dd>
        </dl>
        @if(!in_array($registration['status'][array_keys($registration['status'])[0]], ['Closed', 'Not Open Yet']))
        <div class="col-xs-12 col-sm-offset-2 col-sm-8 text-center">
            {{ displayLeagueBars($league->limits, $league->counts) }}
        </div>
        <div class="col-xs-12">
            <br/><br/>
        </div>
        @endif
    </div>
</div>
@endif
@endsection

@section('page-scripts')
@endsection
