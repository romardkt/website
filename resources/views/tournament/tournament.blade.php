@extends('layouts.tournament')

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-8 description">
        @if($isAuthorized['manager'])
        <div class="pull-right">
            <a class="btn btn-default" href="{{ route('tournament_description_edit', [$tournament->id]) }}"><i class="fa fa-fw fa-lg fa-edit"></i></a>
        </div>
        @endif
        <h1 class="title">{{{ $tournament->display_name }}}</h1>
        {{ $tournament->description }}
    </div>
    <div class="col-xs-12 col-sm-4 feed">
        @if(count($tournament->feed))
        @foreach($tournament->feed as $item)
        <div class="row">
            <div class="col-xs-12">
                @if($isAuthorized['manager'])
                <div class="pull-right">
                    <a class="btn btn-default" href="{{ route('tournament_feed_edit', [$item->id]) }}"><i class="fa fa-fw fa-lg fa-edit"></i></a>
                    <a class="btn btn-danger" href="{{ route('tournament_feed_remove', [$item->id]) }}" onclick="return confirm('Are you sure you want to delete this?');"><i class="fa fa-fw fa-lg fa-trash-o"></i></a>
                </div>
                @endif

                <h4>{{{ $item->title }}}</h4>
                <div class="title text-muted">Posted At {{{ convertDate($item->created_at, 'M j Y @ h:i A') }}}</div>
                <p>{{ $item->content }}</p>
            </div>
        </div>
        <hr/>
        @endforeach
        @else
        <h4 class="text-center">No new news yet</h4>
        @endif
    </div>
</div>
<div class="row tournament-feed">
</div>
@endsection

@section('page-scripts')
@endsection
