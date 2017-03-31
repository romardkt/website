@extends('tournament')

@section('content')
<div class="row location">
    <div class="col-xs-12 col-sm-7 text-center">
        <h3>Map Location</h3>
        <a rel="noopener noreferrer" target="_blank" href="{{ $tournament->location->getUrl() }}"><img src="{{ $tournament->location->getImage(14) }}" alt="Map location image"/></a>
        <p>
            {!! $tournament->location->address() !!}
            <small class="text-muted">Click on map for link</small>
        </p>
    </div>
    <div class="col-xs-12 col-sm-5">
        <h3 class="text-center">Lodging/Information</h3>
        @if(count($tournament->locations))
        @foreach($tournament->locations as $location)
        @can('edit', $tournament)
        <div class="pull-right">
            <div class="btn-group">
                <a class="btn btn-default" href="{{ route('tournament_location_edit', [$location->id]) }}"><i class="fa fa-fw fa-lg fa-edit"></i></a>

                <a class="btn btn-danger" onclick="return confirm('Are you sure?');" href="{{ route('tournament_location_remove', [$location->id]) }}"><i class="fa fa-fw fa-lg fa-trash-o"></i></a>
            </div>
        </div>
        @endif
        @if($location->link !== null)
        <h4><a href="{{ $location->link }}">{{ $location->title }}</a></h4>
        @else
        <h4>{{ $location->title }}</h4>
        @endif
        <p>
            {!! $location->address() !!}
            @if($location->phone !== null)
            {{ $location->phone }}
            @endif

            @if($location->other !== null)
            <dl>
                <dt>Notes:</dt>
                <dd style="margin-left: 10px;">{!! $location->other !!}</dd>
            </dl>
            @endif
        </p>
        <hr/>
        @endforeach
        @else
        <h4 class="text-center">No Specific Information</h4>
        @endif
    </div>
</div>
@endsection

@section('page-scripts')
@endsection
