@section('profile_content')
<legend>My Minors</legend>
<div class="row">
    <div class="col-xs-12">
        <div class="list-group">
            <a href="{{ route('profile_minor_add') }}" class="list-group-item active">Add a minor...</a>
            @forelse($minors as $minor)
            <a href="{{ route('profile_minor_edit', [$minor->id]) }}" class="list-group-item">
                @if($minor->getAge() > 17)
                <div class="badge btn btn-default" onClick="window.location = '{{ route('profile_minors_convert', [$minor->id]) }}'; return false;">Convert Account</div>
                @endif
                <h4 class="list-group-item-heading">
                    {{ $minor->fullname() }}
                    @if($minor->profile->nickname !== null)
                    <span class="text-muted">({{ $minor->profile->nickname }})</span>
                    @endif
                </h4>
                <p class="list-group-item-text">{{ $minor->gender }}, {{ displayAge($minor->birthday) }} years old, {{ displayHeight($minor->profile->height) }}, playing for {{ displayExperience($minor->profile->experience) }}</p>
            </a>
            @empty
            <h4 class="text-center">No minors created yet.</h4>
            @endforelse
        </div>
    </div>
</div>
@endsection

@include('profile.header')
