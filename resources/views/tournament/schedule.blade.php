@extends('tournament')

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @if($isAuthorized['manager'])
        <div class="pull-right">
            <a class="btn btn-default" href="{{ route('tournament_schedule_edit', [$tournament->id]) }}"><i class="fa fa-fw fa-lg fa-edit"></i></a>
        </div>
            @if($tournament->name == 'nationals' && $tournament->year == 2014)
        <h1 class="title">{{{ $tournament->display_name }}} Teams/Schedule</h1>
            @else
        <h1 class="title">{{{ $tournament->display_name }}} Schedule</h1>
            @endif
        @endif

        {{ $tournament->schedule }}
    </div>
</div>
<div class="row tournament-feed">
</div>
@endsection

@section('page-scripts')
@endsection
