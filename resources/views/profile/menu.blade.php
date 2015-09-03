<div class="row visible-xs">
    <div class="col-xs-12">
        <h5>Menu</h5>
        <select class="form-control" onchange="window.location = $(this).val();">
            @foreach(Config::get('cupa.profileMenu') as $route => $name)
            @if($route == 'public_profile')
            <option value="{{ route($route, [$isAuthorized['userData']->id]) }}"{{{ (Route::currentRouteName() == $route) ? ' selected' : '' }}}>{{{ $name }}}</option>
            @else
            <option value="{{{ route($route) }}}"{{{ (Route::currentRouteName() == $route) ? ' selected' : '' }}}>{{{ $name }}}</option>
            @endif
            @endforeach
        </select>
        <hr/>
    </div>
</div>

<div class="row hidden-xs text-left">
    <div class="col-xs-12">
        <h4>Menu</h4>
        <div class="list-group">
            @foreach(Config::get('cupa.profileMenu') as $route => $name)
            @if($route == 'profile_public')
            <a class="list-group-item" href="{{ route('profile_public', [$isAuthorized['userData']->id]) }}">
                {{{ $name }}}
            </a>
            @else
            <a class="list-group-item{{{ (Route::currentRouteName() == $route) ? ' active' : '' }}}" href="{{ route($route) }}">
                {{ ($route == 'profile_leagues') ? '<span class="badge">' . count($leagues) . '</span>' : '' }}
                {{ ($route == 'profile_minors') ? '<span class="badge">' . count($minors) . '</span>' : '' }}
                {{ ($route == 'profile_contacts') ? '<span class="badge">' . count($contacts) . '</span>' : '' }}
                {{{ $name }}}
            </a>
            @endif
            @endforeach
        </div>
    </div>
</div>
