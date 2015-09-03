@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">{{ $league->displayName() }} Teams</h2>
    </div>
</div>
@include('leagues.header')
@if($isAuthorized['manager'])
<div class="row">
    <div class="col-xs-12 text-right">
        <div class="btn-group">
            <a class="btn btn-default" href="{{ route('league_team_add', [$league->slug]) }}"><i class="fa fa-fw fa-lg fa-plus"></i> Add Team</a>
        </div>
    </div>
</div>
<hr/>
@endif
@if(count($league->teams))
<div class="league-teams">
    @foreach($league->teamsByRank() as $i => $team)
    <div class="col-sm-6 league-team">
        <div class="row">
            <div class="col-sm-4 col-lg-3 logo-container">
                <img src="{{ asset($team->logo) }}"/>
            </div>
            <div class="col-sm-8 col-lg-9 title-container">
                <p style="color: {{ $team->color_code }};" class="title">{{{ $team->name }}}</p>
                <div class="row text-muted ranks">
                    <div class="col-xs-12">
                        <p>Rank: <strong class="text-info">{{{ $i + 1 }}}</strong></p>
                        <p>W/L: <strong class="text-info">{{{ $team->record->record() }}}</strong></p>
                        <p>Points: <strong class="text-info">{{{ $team->points() }}}</strong></p>
                    </div>
                </div>
                <p class="captains">
                    <dl>
                    @if($league->is_youth)
                        <dt>Head Coach(es):</dt>
                        <dd>
                            @foreach ($team->headCoaches() as $coach)
                            @if($coach->user->email)
                            {{ secureEmail($coach->user->email, $coach->user->fullname()) }}
                            @else
                            {{ secureEmail($coach->user->parentObj->email, $coach->user->fullname()) }}
                            @endif
                            @endforeach
                        </dd>
                        <dt>Assistant Coach(es):</dt>
                        <dd>
                            @foreach ($team->asstCoaches() as $coach)
                            @if($coach->user->email)
                            {{ secureEmail($coach->user->email, $coach->user->fullname()) }}
                            @else
                            {{ secureEmail($coach->user->parentObj->email, $coach->user->fullname()) }}
                            @endif
                            @endforeach
                        </dd>
                    @else
                        <dt>Captain(s):</dt>
                        <dd>
                            @foreach ($team->captains() as $captain)
                            @if($captain->user->email)
                            {{ secureEmail($captain->user->email, $captain->user->fullname()) }}
                            @else
                            {{ secureEmail($captain->user->parentObj->email, $captain->user->fullname()) }}
                            @endif
                            @endforeach
                        </dd>
                    @endif
                    </dl>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="btn-group btn-group-justified">
                    <a class="btn btn-default show-players-btn" href="#" data-team="{{{ $team->id }}}" title="Show Players"><i class="fa fa-lg fa-fw fa-user"></i><span class="hidden-xs hidden-sm"> Players</span></a>
                    <a class="btn btn-default show-record-btn" href="#" data-team="{{{ $team->id }}}" title="Show Record"><i class="fa fa-lg fa-fw fa-list"></i><span class="hidden-xs hidden-sm"> Record</span></a>
                    @if($isAuthorized['manager'])
                    <a class="btn btn-default" href="{{ route('league_team_edit', [$league->slug, $team->id]) }}" title="Edit Team"><i class="fa fa-lg fa-fw fa-edit"></i><span class="hidden-xs hidden-sm"> Edit</span></a>
                    <a class="btn btn-default" href="{{ route('league_team_remove', [$league->slug, $team->id]) }}" onclick="return confirm('Are you sure you want to delete this team?');" title="Remove Team"><i class="text-danger fa fa-lg fa-fw fa-trash-o"></i><span class="hidden-xs hidden-sm text-danger"> Delete</span></a>
                    @endif
                </div>
            </div>
        </div>
        <br/>
        <br/>
    </div>
    @endforeach
</div>

<div class="modal fade" id="league-players" tabindex="-1" role="dialog" aria-labelledby="league-playersLable" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="league-players-title"></h4>
            </div>
            <div id="league-players-body" class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@else
<h4 class="text-center">There are no teams created yet</h4>
@endif

@endsection

@section('page-scripts')
<script>
$('.show-players-btn').on('click touchstart', function (e) {
    e.preventDefault();
    $.ajax({
        url: '{{ route('league_team_players') }}',
        type: 'get',
        data: {'team_id': $(this).data('team')},
        success: function (resp) {
            if (resp.status != 'error') {
                $('#league-players-title').html(resp.title);
                $('#league-players-body').html(resp.body);
                $('#league-players').modal('show');
            } else {
                console.log(resp);
            }
        }
    });
});

$('.show-record-btn').on('click touchstart', function (e) {
    e.preventDefault();
    $.ajax({
        url: '{{ route('league_team_record') }}',
        type: 'get',
        data: {'team_id': $(this).data('team')},
        success: function (resp) {
            if (resp.status != 'error') {
                $('#league-players-title').html(resp.title);
                $('#league-players-body').html(resp.body);
                $('#league-players').modal('show');
            } else {
                console.log(resp);
            }
        }
    });
});
</script>
@endsection
