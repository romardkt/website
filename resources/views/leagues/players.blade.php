@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">{{ $league->displayName() }} Players</h2>
    </div>
</div>
@include('leagues.header')
@if($isAuthorized['manager'])
<div class="row">
    <div class="col-sm-8 text-left">
        <h4>
        # of players: <span class="text-info">{{{ $league->counts->total }}}</span> (<span class="text-info">{{{ $league->counts->male }}}</span> Male, <span class="text-info">{{{ $league->counts->female }}}</span> Female, <span class="text-info">{{{ $league->counts->other }}}</span> Unknown)
        </h4>
    </div>
    <div class="col-sm-4 text-right">
        <div class="btn-group">
            <a class="btn btn-default" href="{{ route('league_players_download', [$league->slug]) }}"><i class="fa fa-fw fa-lg fa-list"></i> Export</a>
        </div>
    </div>
</div>
<hr/>
@endif
@if(count($players))
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <table class="players table table-condensed table-hover table-responsive">
            <thead>
                <tr>
                    <th class="col-xs-5 col-sm-3">Name</th>
                    <th class="col-xs-6 col-sm-3">Team</th>
                    <th class="col-xs-1 text-center hidden-xs">Gender</th>
                    <th class="col-xs-1 text-center hidden-xs">Age</th>
                    <th class="col-xs-1 text-center hidden-xs">Height</th>
                    <th class="col-xs-1 text-center hidden-xs">Years Played</th>
                    <th class="col-xs-3 text-center hidden-xs">Highest Level</th>
                </tr>
            </thead>
            <tbody>
                @foreach($players as $player)
                <tr>
                    <td class="col-xs-5 col-sm-3">{{ secureEmail($player['email'], $player['first_name'] . ' ' . $player['last_name']) }}</td>
                    <td class="col-xs-6 col-sm-3">{{{ (empty($player['team_name'])) ? 'Not Assigned' : $player['team_name'] }}}</td>
                    <td class="col-xs-1 text-center hidden-xs">{{{ $player['gender'] }}}</td>
                    <td class="col-xs-1 text-center hidden-xs">{{{ displayAge($player['birthday']) }}}</td>
                    <td class="col-xs-1 text-center hidden-xs">{{{ displayHeight($player['height']) }}}</td>
                    <td class="col-xs-1 text-center hidden-xs">{{{ displayExperience($player['experience']) }}}</td>
                    <td class="col-xs-3 text-center hidden-xs">{{{ displayLevel($player['level']) }}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<h4 class="text-center">There are no players right now</h4>
@endif
@endsection

@section('page-scripts')
@endsection
