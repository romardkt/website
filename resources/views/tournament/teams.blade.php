@extends('tournament')

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <h1 class="title">{{{ $tournament->display_name }}} Teams</h1>
        <ul class="nav nav-tabs">
            @foreach(json_decode($tournament->divisions) as $division)
                <li{{ ($currentDivision == $division) ? ' class="active"' : '' }}><a href="{{ route('tournament_teams', [$tournament->name, $tournament->year, $division])}}">{{{ ucwords(str_replace('_', ' ', $division)) }}}</a></li>
            @endforeach
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <div class="list-group">
        @foreach($tournament->fetchTeams($currentDivision) as $team)
        <div class="list-group-item">
            @if($isAuthorized['manager'])
            <div class="pull-right">
                <div class="btn-group">
                    <a class="btn btn-default" href="{{ route('tournament_teams_edit', [$team->id]) }}"><i class="fa fa-fw fa-lg fa-edit"></i></a>
                    <a class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this team?');" href="{{ route('tournament_teams_remove', [$team->id]) }}"><i class="fa fa-fw fa-lg fa-trash-o"></i></a>
                </div>
            </div>
            @endif
            <h4 class="list-group-item-heading">
                {{{ $team->name }}}
            </h4>
            <p class="list-group-item-text">
                {{{ $team->city }}}, {{{ $team->state }}}<br/>
                {{ ($team->paid == 1) ? '<div class="label label-success">Paid</div>' : '<div class="label label-danger">Not Paid</div>' }}
                {{ ($team->accepted == 1) ? '<div class="label label-success">Accepted</div>' : '<div class="label label-danger">Not Accepted</div>' }}
            </p>

        </div>
        @endforeach
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
@endsection
