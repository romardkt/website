        <div class="row visible-xs">
            <select id="profile-page" onchange="if($(this).val() != '#') {window.location = $(this).val();}">
                <option value="#">Select page</option>
                <option value="{{ route('manage_unpaid') }}"{{ (Route::currentRouteName() == 'manage_unpaid') ? ' selected' : '' }}>Unpaid List</option>
                <option value="{{ route('manage_league_players') }}"{{ (Route::currentRouteName() == 'manage_league_players') ? ' selected' : '' }}>League Players</option>
                @if(Auth::check())
                <option value="{{ route('manage_duplicates') }}"{{ (Route::currentRouteName() == 'manage_duplicates') ? ' selected' : '' }}>Duplicate Users</option>
                <option value="{{ route('manage_forms') }}"{{ (Route::currentRouteName() == 'manage_forms') ? ' selected' : '' }}>Forms</option>
                <option value="{{ route('manage_users') }}"{{ (Route::currentRouteName() == 'manage_users') ? ' selected' : '' }}>Users</option>
                @endif
            </select>
        </div>

        <div class="row hidden-xs text-left">
            <h4>Menu</h4>
            <div class="list-group">
                <a class="list-group-item{{ (Route::currentRouteName() == 'manage_unpaid') ? ' active' : '' }}" href="{{ route('manage_unpaid') }}">Unpaid List</a>
                <a class="list-group-item{{ (Route::currentRouteName() == 'manage_league_players') ? ' active' : '' }}" href="{{ route('manage_league_players') }}">League Players</a>
                @if(0)
                <a class="list-group-item{{ (Route::currentRouteName() == 'manage_users') ? ' active' : '' }}" href="{{ route('manage_users') }}">Users</a>
                @endif
                @if(Auth::check())
                <a class="list-group-item{{ (Route::currentRouteName() == 'manage_duplicates') ? ' active' : '' }}" href="{{ route('manage_duplicates') }}">Duplicate Users</a>
                <a class="list-group-item{{ (Route::currentRouteName() == 'manage_forms') ? ' active' : '' }}" href="{{ route('manage_forms') }}">Forms</a>
                <a class="list-group-item{{ (Route::currentRouteName() == 'manage_users') ? ' active' : '' }}" href="{{ route('manage_users') }}">Users</a>
                @endif
            </div>
        </div>
