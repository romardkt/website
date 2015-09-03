        <div class="form-group">
            {{ Form::label('Date')}}
            {{ Form::text('played_at_date', (isset($game->played_at)) ? convertDate($game->played_at, 'm/d/Y') : null, ['class' => 'datepicker text-center form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('Time')}}
            {{ Form::text('played_at_time', (isset($game->played_at)) ? convertDate($game->played_at, 'h:i A') : null, ['class' => 'clockpicker text-center form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('Week #')}}
            {{ Form::number('week', null, ['class' => 'text-center form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('Field #')}}
            {{ Form::number('field', null, ['class' => 'text-center form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('Game Placeholder') }}
            <div class="radio">
                <p>{{ Form::radio('status', 'game_on', (!isset($game->status) || $game->status == 'game_on') ? true : false, ['class' => 'game-status']) }} Game On!</p>
                <p>{{ Form::radio('status', 'canceled', (isset($game->status) && $game->status == 'canceled') ? true : false, ['class' => 'game-status']) }} Games Canceled</p>
                <p>{{ Form::radio('status', 'playoff', (isset($game->status) && $game->status == 'playoff') ? true : false, ['class' => 'game-status']) }} Playoff Placeholder</p>
            </div>
        </div>

        @foreach(['away', 'home'] as $teamType)
        <?php
            $options = $teamType . 'Teams';
            $values = [];
        ?>

        <div class="form-group team-select">
            @if ($league->has_pods)
            @if(isset($$options))
            @foreach($$options as $team)
            <?php $values[] = $team->league_team_id; ?>
            @endforeach
            @endif
            {{ Form::label(ucfirst($teamType) . ' Pods') }}
            {{ Form::select($teamType . '_team[]', $leagueTeams, $values, ['class' => 'form-control select2', 'multiple']) }}
            <span class="help-block">Click to select the teams</span>
            @else
            <?php $currentTeam = (!empty($$options)) ? array_shift($$options) : null; ?>
            {{ Form::label(ucfirst($teamType) . ' Team') }}
            {{ Form::select($teamType . '_team[]', $leagueTeams, (!empty($currentTeam->league_team_id)) ? $currentTeam->league_team_id : null, ['class' => 'form-control select2']) }}
            @endif
        </div>

        <div class="form-group team-select">
            {{ Form::label(ucfirst($teamType) . ' Score') }}
            {{ Form::number($teamType . '_score', (!empty($currentTeam->score)) ? $currentTeam->score : null, ['class' => 'form-control']) }}
            <span class="help-block">Leave blank for no score</span>
        </div>
        @endforeach

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">{{ $submitText }}</button>
                <a class="btn btn-default" href="{{ route('league_schedule', [$league->slug]) }}">Cancel</a>
                @if($submitText == 'Update Game')
                <a class="btn btn-danger" onclick="return confirm('Are you sure?');"href="{{ route('league_schedule_remove', [$league->slug, $game->id]) }}"><i class="fa fa-fw fa-lg fa-trash-o"></i> Remove</a>
                @endif
            </div>
        </div>

@section('page-scripts')
<script>
$('.datepicker').pickadate({
    format: 'mm/dd/yyyy',
    editable: false,
    selectYears: true,
    selectMonths: true
});
$('.clockpicker').clockpicker({
    donetext: 'Done',
    twelvehour: true,
    align: 'right',
});
$('.select2').select2({
    placeholder: 'Select a location'
});
$('.game-status').on('click', function(e){
    if($(this).val() == 'game_on') {
        $('.team-select').fadeIn();
    } else {
        $('.team-select').fadeOut();
    }

});

if($('.game-status:checked').val() == 'game_on') {
    $('.team-select').fadeIn();
} else {
    $('.team-select').fadeOut();
}
</script>
@endsection
