<div class="list-group">
    @if(count($players))
    @foreach($players as $player)
    <a class="list-group-item" href="{{ route('profile_public', [$player['slug']]) }}">
        <span class="badge">{{ $player['height'] }}</span>
        <p><strong>{{ $player['name'] }}</strong> - {!! $player['level'] !!}</p>
    </a>
    @endforeach
    @else
    <div class="list-group-item record">
        <h4 class="list-group-item-heading text-center">No Players Added</h4>
    </div>
    @endif
</div>
