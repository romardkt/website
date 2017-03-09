        <div class="row visible-xs">
            <select id="profile-page" onchange="if($(this).val() != '#') {window.location = $(this).val();}">
                <option value="#">Select page</option>
                @can('is-director')
                <option value="{{ route('manage_unpaid') }}"{{ (Route::currentRouteName() == 'manage_unpaid') ? ' selected' : '' }}>Unpaid List</option>
                @endif
                <option value="{{ route('manage_waivers') }}"{{ (Route::currentRouteName() == 'manage_waivers') ? ' selected' : '' }}>Waivers/Medical Release</option>

                @can('is-manager')
                <option value="{{ route('manage_league_players') }}"{{ (Route::currentRouteName() == 'manage_league_players') ? ' selected' : '' }}>League Players</option>
                <option value="{{ route('manage_coaches') }}"{{ (Route::currentRouteName() == 'manage_coaches') ? ' selected' : '' }}>All Coaches</option>
                <option value="{{ route('manage_files') }}"{{ (Route::currentRouteName() == 'manage_files') ? ' selected' : '' }}>Files</option>
                <option value="{{ route('manage_reports') }}"{{ (Route::currentRouteName() == 'manage_reports') ? ' selected' : '' }}>Reports</option>
                @endif

                @can('is-volunteer')
                <option value="{{ route('manage_users') }}"{{ (Route::currentRouteName() == 'manage_users') ? ' selected' : '' }}>Users</option>
                <option value="{{ route('manage_volunteers') }}"{{ (Route::currentRouteName() == 'manage_volunteers') ? ' selected' : '' }}>Volunteers</option>
                @endif

                @can('is-admin')
                <option value="{{ route('manage_duplicates') }}"{{ (Route::currentRouteName() == 'manage_duplicates') ? ' selected' : '' }}>Duplicate Users</option>
                <option value="{{ route('manage_forms') }}"{{ (Route::currentRouteName() == 'manage_forms') ? ' selected' : '' }}>Forms</option>
                @endif
            </select>
        </div>

        <div class="row hidden-xs text-left">
            <h4>Menu</h4>
            <div class="list-group">
                @can('is-director')
                <a class="list-group-item{{ (Route::currentRouteName() == 'manage_unpaid') ? ' active' : '' }}" href="{{ route('manage_unpaid') }}">Unpaid List</a>
                @endif
                <a class="list-group-item{{ (Route::currentRouteName() == 'manage_waivers') ? ' active' : '' }}" href="{{ route('manage_waivers') }}">Waivers/Medical Release</a>

                @can('is-manager')
                <a class="list-group-item{{ (Route::currentRouteName() == 'manage_league_players') ? ' active' : '' }}" href="{{ route('manage_league_players') }}">League Players</a>
                <a class="list-group-item{{ (Route::currentRouteName() == 'manage_coaches') ? ' active' : '' }}" href="{{ route('manage_coaches') }}">All Coaches</a>
                <a class="list-group-item{{ (Route::currentRouteName() == 'manage_files') ? ' active' : '' }}" href="{{ route('manage_files') }}">Files</a>
                <a class="list-group-item{{ (Route::currentRouteName() == 'manage_reports') ? ' active' : '' }}" href="{{ route('manage_reports') }}">Reports</a>
                @endif

                @can('is-volunteer')
                <a class="list-group-item{{ (Route::currentRouteName() == 'manage_users') ? ' active' : '' }}" href="{{ route('manage_users') }}">Users</a>
                <a class="list-group-item{{ (Route::currentRouteName() == 'manage_volunteers') ? ' active' : '' }}" href="{{ route('manage_volunteers') }}">Volunteers</a>
                @endif
                @can('is-admin')
                <a class="list-group-item{{ (Route::currentRouteName() == 'manage_duplicates') ? ' active' : '' }}" href="{{ route('manage_duplicates') }}">Duplicate Users</a>
                <a class="list-group-item{{ (Route::currentRouteName() == 'manage_forms') ? ' active' : '' }}" href="{{ route('manage_forms') }}">Forms</a>
                @endif
            </div>
        </div>
