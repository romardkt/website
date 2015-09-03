@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">{{ $league->displayName() }} Players</h2>
    </div>
</div>
@include('leagues.header')
@if(count($players))
<div class="row">
    <div class="col-xs-12 text-right">
        <div class="btn-group">
            <a class="btn btn-default" href="{{ route('league_waitlist_download', [$league->slug]) }}"><i class="fa fa-fw fa-lg fa-list"></i> Export</a>
        </div>
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <table class="players table table-condensed table-hover table-responsive">
            <thead>
                <tr>
                    <th class="col-xs-5">Name</th>
                    <th class="col-xs-1 text-center">Gender</th>
                    <th class="col-sm-1 text-center hidden-xs">Age</th>
                    <th class="col-xs-4 text-center">Registered</th>
                    <th class="col-xs-1 text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($players as $player)
                <tr>
                    <td class="col-xs-5">{{ secureEmail($player->user->email, $player->user->fullname()) }}</td>
                    <td class="col-xs-1 text-center">{{{ $player->user->gender }}}</td>
                    <td class="col-xs-1 text-center hidden-xs">{{{ displayAge($player->user->birthday) }}}</td>
                    <td class="col-xs-4 text-center">{{{ (new DateTime($player->created_at))->format('M j Y h:i A') }}}</td>
                    <td class="col-xs-1 text-center">
                        <a class="btn btn-success" href="{{ route('league_waitlist_accept', [$league->slug, $player->id]) }}">
                            <i class="fa fa-fw fa-lg fa-check"></i><span class="hidden-xs"> Accept Player</span>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<h4 class="text-center">There are no players wait-listed right now</h4>
@endif
@endsection

@section('page-scripts')
@endsection
