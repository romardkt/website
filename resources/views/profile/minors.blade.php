@section('profile_content')
<legend>My Minors</legend>
<div class="row">
    <div class="col-xs-12">
        <div class="list-group">
            <a href="{{ route('profile_minor_add') }}" class="list-group-item active">Add a minor...</a>
            @foreach($minors as $minor)
            <a href="{{ route('profile_minor_edit', [$minor->id]) }}" class="list-group-item">
                <h4 class="list-group-item-heading">
                    {{ $minor->fullname() }}
                    @if($minor->profile->nickname !== null)
                    <span class="text-muted">({{ $minor->profile->nickname }})</span>
                    @endif
                </h4>
                <p class="list-group-item-text">{{ $minor->gender }}, {{ displayAge($minor->birthday) }} years old, {{ displayHeight($minor->profile->height) }}, playing for {{ displayExperience($minor->profile->experience) }}</p>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection

@include('profile.header')
