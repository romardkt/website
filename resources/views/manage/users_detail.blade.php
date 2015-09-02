<h1>{{ $user->fullname() }}</h1>

<div class="btn-group">
    @if(Auth::check())
    <a class="btn btn-default" href="{{ route('manage_impersonate', [$user->id]) }}"><i class="fa fa-fw fa-lg fa-user"></i> Impersonate</a>
    @endif
    @if($user->is_active == 1)
    <button class="btn btn-danger"><i class="fa fa-fw fa-lg fa-times"></i> Deactivate</button>
    @else
    <button class="btn btn-success"><i class="fa fa-fw fa-lg fa-check"></i> Activate</button>
    @endif
</div>
