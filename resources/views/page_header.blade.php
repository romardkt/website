<div class="row banner">
    <img src="{{ fetchBanner($page->route) }}" alt="Page Banner Image"/>
    <div class="col-xs-12 ">
        <div class="page-actions">
            <div class="btn-group pull-right">
                @if($actions == 'admin_edit')
                    @can('is-admin')
                <a class="btn btn-default" href="{{ route($page->route . '_admin') }}"><i class="fa fa-lg fa-cogs"></i> Admin</a>
                    @endif
                    @can('is-editor')
                <a class="btn btn-default" href="{{ route($page->route . '_edit') }}"><i class="fa fa-lg fa-edit"></i> Edit</a>
                    @endif
                @elseif($actions == 'edit' && Gate::allows('is-editor'))
                <a class="btn btn-default" href="{{ route($page->route . '_edit') }}"><i class="fa fa-lg fa-edit"></i> Edit</a>
                @elseif($actions == 'add' && Gate::allows('is-manager'))
                <a class="btn btn-default" href="{{ route($page->route . '_add') }}"><i class="fa fa-lg fa-plus"></i> Add {{ $page->display }}</a>
                @elseif($actions == 'add_edit')
                    @can('is-manager')
                <a class="btn btn-default" href="{{ route($page->route . '_add') }}"><i class="fa fa-lg fa-plus"></i> Add {{ $page->display }}</a>
                    @endif
                    @can('is-editor')
                <a class="btn btn-default" href="{{ route($page->route . '_edit') }}"><i class="fa fa-lg fa-edit"></i> Edit</a>
                    @endif
                @elseif(substr($actions, 0, 4) == 'team')
                    @if(Gate::allows('edit', $team))
                <?php list($route, $name) = explode(',', $actions); ?>
                <a class="btn btn-default" href="{{ route($route, [$name]) }}"><i class="fa fa-lg fa-edit"></i> Edit {{ ucwords(str_replace('-', ' ', $name)) }}</a>
                    @endif
                @elseif(substr($actions, 0, 5) == 'youth')
                    @can('is-editor')
                <?php list($route, $name) = explode(',', $actions); ?>
                <a class="btn btn-default" href="{{ route($route, [$name]) }}"><i class="fa fa-lg fa-edit"></i> Edit {{ ucwords(str_replace('_', ' ', $name)) }}</a>
                    @endif
                @elseif($actions == 'league_add')
                    @can('is-manager')
                <a class="btn btn-default" href="{{ route($actions, [$season]) }}"><i class="fa fa-lg fa-plus"></i> New {{ ucfirst($season) }} League</a>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@include('submenu')
