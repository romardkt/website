@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">{{ $league->displayName() }} Schedule</h2>
    </div>
</div>
@include('leagues.header')
@can('edit', $league)
<div class="row">
    <div class="col-xs-12 col-sm-6 text-center">
        <p>To edit a game just click on the game.</p>
    </div>
    <div class="col-xs-12 col-sm-6 text-right">
        <div class="btn-group">
            <a class="btn btn-default" href="{{ route('league_schedule_add', [$league->slug]) }}"><i class="fa fa-fw fa-lg fa-plus"></i> Add Game</a>
        </div>
    </div>
</div>
<hr/>
@endif
@if(count($games))
<div class="row league-games">
    <?php $currentWeek = 0; ?>
    @foreach($games as $game)
        @if($currentWeek != $game->week)
        @if($currentWeek > 0)
            </div>
        </div>
        @endif
        <?php $currentWeek = $game->week;?>
        <a name="week{{ $game->week }}"></a>
        <div class="col-sm-4 col-sm-offset-1">
            <h3>
                Week {{ $game->week }}
                {!! generateSocailShareButtons('right', route('league_schedule', [$league->slug]).'#week'.$game->week) !!}
            </h3>
        </div>
        <div class="col-sm-6 text-right">
            <br/>
            @can('edit', $league)
            Set week {{ $game->week }} games to:
            <a href="{{ route('league_schedule_markall', [$league->slug, $game->week, 'game_on']) }}">Game On</a> |
            <a href="{{ route('league_schedule_markall', [$league->slug, $game->week, 'gametime_decision']) }}">Gametime Decision</a> |
            <a href="{{ route('league_schedule_markall', [$league->slug, $game->week, 'cancelled']) }}">Cancelled</a>
            @endif
        </div>
        <div class="col-xs-12 col-sm-offset-1 col-sm-10 league-game-week">
            <div class="league-game list-group">
        @endif
        <?php
            $away = [];
            $home = [];
        ?>
        @foreach($game->teams as $team)
        <?php
            ${$team->type}[] = $team;
        ?>
        @endforeach
            <a class="row list-group-item" {!! (Gate::allows('edit', $league)) ? ' href="' . route('league_schedule_edit', [$league->slug, $game->id]) . '"' : '' !!}>
                <div class="when pull-left">
                    <div class="month">{{ date('M', strtotime($game->played_at)) }}</div>
                    <div class="day">{{ date('d', strtotime($game->played_at)) }}</div>
                    <div class="year">{{ date('Y', strtotime($game->played_at)) }}</div>
                    <div class="time">{{ date('h:i A', strtotime($game->played_at)) }}</div>
                </div>
                <span class="badge schedule">
                    {{$game->score()}}
                </span>
                <h4>
                    @if($game->status != 'playoff')
                    <?php
                        $awayTeams = [];
                        foreach ($away as $awayTeam) {
                            $awayTeams[] = $awayTeam->team->name;
                        }
                    ?>
                    @if($game->status == 'cancelled')
                    <del>
                    @endif
                    <span class="{{ $awayTeam->getClassText() }}">{{{ implode(' & ', $awayTeams) }}}</span>
                    vs<br class="visible-xs"/>
                    <?php
                        $homeTeams = [];
                        foreach ($home as $homeTeam) {
                            $homeTeams[] = $homeTeam->team->name;
                        }
                    ?>
                    <span class="{{ $homeTeam->getClassText() }}">{{{ implode(' & ', $homeTeams) }}}</span>
                    @if($game->status == 'cancelled')
                    </del> &nbsp;<span class="text-danger">CANCELLED</span>
                    @elseif($game->status == 'gametime_decision')
                     &nbsp;<span class="text-warning">GAME TIME DECISION</span>
                    @endif

                    @elseif($game->status == 'playoff')
                    Playoffs Game Placeholder
                    @endif
                </h4>
                <p><strong>Field #{{{ $game->field }}}</strong></p>
            </a>
    @endforeach
        </div>
    </div>
</div>
@else
<h4 class="text-center">{{{ (count($league->teams) > 2) ? 'There are no games created yet' : 'There needs to be 2 teams to have a schedule' }}}</h4>
@endif
@endsection

@section('page-scripts')
@endsection
