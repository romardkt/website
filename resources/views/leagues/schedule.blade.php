@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">{{ $league->displayName() }} Schedule</h2>
    </div>
</div>
@include('leagues.header')
@if($isAuthorized['manager'])
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
        <h3 class="col-xs-12 text-center">Week {{{ $game->week }}}</h3>
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
            <a class="row list-group-item" {{ ($isAuthorized['manager']) ? ' href="' . route('league_schedule_edit', [$league->slug, $game->id]) . '"' : '' }}>
                <div class="when pull-left">
                    <div class="month">{{ date('M', strtotime($game->played_at)) }}</div>
                    <div class="day">{{ date('d', strtotime($game->played_at)) }}</div>
                    <div class="year">{{ date('Y', strtotime($game->played_at)) }}</div>
                    <div class="time">{{ date('h:i A', strtotime($game->played_at)) }}</div>
                </div>
                <span class="badge schedule">
                    @if($game->status == 'game_on')
                    @if ($away[0]->score != $home[0]->score) {{{ $away[0]->score }}} - {{{ $home[0]->score }}} @else N/A @endif
                    @else
                    N/A
                    @endif
                </span>
                <h4>
                    @if($game->status == 'game_on' || $game->status == 'canceled')
                    <?php
                        $awayTeams = [];
                        foreach ($away as $awayTeam) {
                            $awayTeams[] = $awayTeam->team->name;
                        }
                    ?>
                    @if($game->status == 'canceled')
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
                    @if($game->status == 'canceled')
                    </del> &nbsp;<span class="text-danger">CANCELED</span>
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
