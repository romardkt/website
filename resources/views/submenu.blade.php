<div class="row submenu">
    <nav class="navbar navbar-default {{ $pageRoot }}" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#submenu-collapse">
                    <i class="fa fa-fw fa-lg fa-bars"></i>
                </button>
                <a class="visible-xs navbar-brand" href="#">{{ ucwords($pageRoot) }}</a>
            </div>

            <div class="collapse navbar-collapse" id="submenu-collapse">
                <ul class="nav navbar-nav">
                    @if($pageRoot == 'teams')
                    @foreach($subMenus as $subMenu)
                    <?php $active = (isset($team->name) && $subMenu->name == $team->name) ? ' class="active"' : ''; ?>
                    <li{!! $active !!}><a href="{{ route('teams_show', array($subMenu->name)) }}">{{{ $subMenu->menu }}}</a></li>
                    @endforeach
                    @else
                    @foreach($subMenus as $subMenu)
                        <?php $active = ($subMenu->route == $page->route) ? ' class="active"' : '';?>
                        @if(in_array($subMenu->route, ['youth_ycc', 'around_fields', 'around_discgolf']))
                            <li{{ $active }}><a target="_blank" href="{{ route($subMenu->route) }}">{{{ $subMenu->display }}}</a></li>
                        @elseif($subMenu->route == 'volunteer_list')
                            @can('is-volunteer')
                            <li{{ $active }}><a href="{{ route($subMenu->route) }}">{{{ $subMenu->display }}}</a></li>
                            @endif
                        @elseif($subMenu->route == 'youth_leagues' && isset($league->is_youth) && isset($league->is_youth))
                            <li class="active"><a href="{{ route($subMenu->route) }}">{{{ $subMenu->display }}}</a></li>
                        @else
                            <li{!! $active !!}><a href="{{ route($subMenu->route) }}">{{{ $subMenu->display }}}</a></li>
                        @endif
                    @endforeach
                    @if($pageRoot == 'about')
                            <li{!! $active !!}><a href="{{ route('scholarship_hoy') }}">Chris Hoy Memorial Scholarship</a></li>
                    @endif
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</div>
