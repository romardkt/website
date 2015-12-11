<div class="row league-menu">
    <div class="col-xs-12 text-center">
        <div class="btn-group hidden-xs">
            <a class="btn btn-default" href="{{ route('league', [$league->slug]) }}">League Info</a>
            @foreach(Config::get('cupa.league_menu') as $route => $data)
            @if($data['auth'] === null || Gate::allows('coach', $league) && ($data['auth'] == 'coach' && $league->is_youth == 1))
            <a class="btn btn-default{{{ (Route::currentRouteName() == $route) ? ' active' : '' }}}" href="{{ route($route, [$league->slug]) }}">{{{ $data['name'] }}}</a>
            @endif
            @endforeach
        </div>
    </div>
    <div class="col-xs-12 text-center">
        <div class="btn-group btn-group-justified hidden-xs">
            @foreach(Config::get('cupa.league_manage_menu') as $route => $data)
            @if($data['auth'] === null || Gate::allows($data['auth'], $league))
            @if(($route != 'league_requests' && $route != 'league_coaches') || ($league->user_teams == 1 && $route == 'league_requests') || ($league->is_youth == 1 && $route == 'league_coaches'))
            <a class="btn btn-default{{{ (Route::currentRouteName() == $route) ? ' active' : '' }}}" href="{{ route($route, [$league->slug]) }}">{{{ $data['name'] }}}</a>
            @endif
            @endif
            @endforeach
        </div>
        <select class="visible-xs form-control" onchange="window.location = $(this).val();">
            <option value="{{ route('league', [$league->slug]) }}">League Info</option>
            @foreach(Config::get('cupa.league_menu') as $route => $data)
            @if($data['auth'] === null || Gate::allows('coach', $league) && ($data['auth'] == 'coach' && $league->is_youth == 1))
            <option value="{{ route($route, [$league->slug]) }}"{{{ (Route::currentRouteName() == $route) ? ' selected' : '' }}}>{{{ $data['name'] }}}</option>
            @endif
            @endforeach
            @foreach(Config::get('cupa.league_manage_menu') as $route => $data)
            @if($data['auth'] === null || Gate::allows($data['auth'], $league))
            @if(($route != 'league_requests' && $route != 'league_coaches') || ($league->user_teams == 1 && $route == 'league_requests') || ($league->is_youth == 1 && $route == 'league_coaches'))
            <option value="{{ route($route, [$league->slug]) }}"{{{ (Route::currentRouteName() == $route) ? ' selected' : '' }}}>{{{ $data['name'] }}}</option>
            @endif
            @endif
            @endforeach
        </select>
    </div>
</div>
<hr/>
