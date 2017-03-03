@extends('app')

@section('content')
@include('page_header')

<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <h2 class="text-center">Pickup Games</h2>
    </div>
</div>
<hr/>
<div class="row pickups">
    <div class="col-xs-12">
            @foreach($pickups as $pickup)
            <div class="col-xs-12 col-sm-6 col-lg-4 pickup">
                <div class="map">
                    <a target="_new" href="{{ $pickup->location->getUrl() }}"><img src="{{ $pickup->location->getImage() }}" alt="{{ $pickup->title }}"/></a>
                </div>
                @can('edit', $pickup)
                <div class="edit-btn">
                    <a class="btn btn-default" href="{{ route('around_pickups_edit', [$pickup->id]) }}"><i class="fa fa-fw fa-lg fa-edit"></i> Edit Pickup</a>
                    @can('delete', $pickup)
                    <a class="btn btn-danger" onclick="return confirm('Are you sure?');" href="{{ route('around_pickups_remove', [$pickup->id]) }}"><i class="fa fa-fw fa-lg fa-trash-o"></i></a>
                    @endif
                </div>
                @endif
                <div class="title"><h3>{{{ $pickup->title }}}</h3></div>
                <div class="datetime"><em>{{{ $pickup->day }}} {{{ $pickup->time }}}</em></div>
                <div class="contacts">
                    <strong>Contact(s):</strong>
                    @if(count($pickup->contacts))
                    @foreach ($pickup->contacts as $contact) {!! secureEmail($contact->user->email, $contact->user->fullname()) !!}
                    @endforeach
                    @else
                    <span class="text-info">Unknown</span>
                    @endif
                </div>
                <hr/>
                <div class="info">{!! $pickup->info !!}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection

@section('page-scripts')

@endsection
