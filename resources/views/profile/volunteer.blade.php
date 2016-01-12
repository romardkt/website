@section('profile_content')
<legend>My Volunteering</legend>
<div class="row">
    <div class="col-xs-12">
        <div class="list-group">
            @foreach($signups as $signup)
            <div class="list-group-item">
                <span class="badge">{{date('m/d/Y', strtotime($signup->event->start))}}</span>
                <h4 class="list-group-item-heading">
                    {{$signup->event->title}}
                </h4>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@include('profile.header')
