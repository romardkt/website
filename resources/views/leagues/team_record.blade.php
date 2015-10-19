<div class="list-group records">
    @if(count($records))
    @foreach($records as $record)
    <div class="list-group-item record">
        <span class="badge {{ $record['result'] }}">{{ $record['score'] }}</span>
        <h4 class="list-group-item-heading">{{ $record['team'] }}</h4>
        <p class="text-muted">Week {{ $record['week'] }}, Field {{ $record['field'] }}</p>
    </div>
    @endforeach
    @else
    <div class="list-group-item record">
        <h4 class="list-group-item-heading text-center">No Games Added</h4>
    </div>
    @endif
</div>
