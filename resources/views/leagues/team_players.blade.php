<div class="list-group league-player-list">
    @if(count($players))
    @foreach($players as $player)
    <div class="list-group-item">
        <span class="badge">{{ $player['height'] }}</span>
        @if($isCoach)
            <a class="badge" target="_new" href="{{route('waiver_export', [$player['year'], $player['id']])}}">
                <i class="fa fa-fw fa-download" title="Download Waiver"></i>
            </a>
        @endif
        <a class="player" href="{{ route('profile_public', [$player['slug']]) }}">
            <strong>{{ $player['name'] }}</strong> - {!! $player['level'] !!}
        </a>
    </div>
    @endforeach
    @else
    <div class="list-group-item record">
        <h4 class="list-group-item-heading text-center">No Players Added</h4>
    </div>
    @endif
</div>
