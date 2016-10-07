@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <h2 class="page">CUPA Management</h2>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-2 text-center">
        @include('manage.menu')
    </div>
    <div class="col-xs-12 col-sm-8" id="players">
        <legend>Waivers/Medical Releases</legend>

        <input class="search form-control" placeholder="Filter by name" type="text"/>

        <table class="table">
            <thead>
                <th>Team</th>
                <th>Name</th>
                <th>Type</th>
            </thead>
            <tbody class="list">
                @forelse($waivers as $leagueId => $waivers)
                <tr><td colspan="3"><h3>{{$waivers[0]['league']->displayName()}} - {{$waivers[0]['position']}}</h3></td></tr>
                @foreach($waivers as $waiver)
                <tr>
                    <td class="name">{{$waiver['team']->name}}</td>
                    <td class="team">{{$waiver['user']->fullname()}}</td>
                    <td>
                        @if($waiver['release'])
                        <a class="btn btn-default" href="{{ route('waiver_export', [$waiver['release']['year'], $waiver['release']['user_id']]) }}">Show Release</a>
                        @else
                        <a class="btn btn-default" href="{{ route('waiver_export', [$waiver['waiver']['year'], $waiver['waiver']['user_id']]) }}">Show Waiver</a>
                        @endif
                    </td>
                </tr>
                @endforeach
                @empty
                <tr><td colspan="4"><h4 class="text-center">No access to any waivers/releases</h4></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('page-scripts')
<script>
var options = {
  valueNames: [ 'name', 'team' ],
  page: 400
};
var playerList = new List('players', options);
</script>
@endsection
