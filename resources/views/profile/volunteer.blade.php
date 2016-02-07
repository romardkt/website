@section('profile_content')
<legend>My Volunteering</legend>
<div class="row">
    <div class="col-xs-12">
        <div class="list-group">
            @forelse($signups as $signup)
            <div class="list-group-item">
                <span class="badge">{{date('m/d/Y', strtotime($signup->event->start))}}</span>
                <h4 class="list-group-item-heading">
                    {{$signup->event->title}}
                </h4>
            </div>
            @empty
            <h4 class="text-center">You have not signed up for any volunteer opportunities.</h4>
            @endforelse
        </div>
    </div>
</div>
@endsection

@include('profile.header')
