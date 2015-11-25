@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-left">
        <div class="btn-group">
            <a class="btn btn-default" href="{{ route('league_manage', [$league->slug]) }}"><i class="fa fa-fw fa-lg fa-arrow-left"></i> Back to Manage</a>
        </div>
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-xs-12 col-sm-6">
        <h2 class="text-center">Add Player(s)</h2>
        <div class="list-group available-players manage-list">
            @if(count($availablePlayers))
            @foreach($availablePlayers as $aPlayer)
            <a id="user-{{{ $aPlayer->id }}}" class="list-group-item player-select">
                <h4>{{{ $aPlayer->first_name . ' ' . $aPlayer->last_name }}}</h4>
            </a>
            @endforeach
            @else
            <a class="list-group-item">
                <h4>No available players</h4>
            </a>
            @endif
        </div>
        <button id="add-players-btn" type="button" class="btn btn-primary col-xs-12"><i class="fa fa-fw fa-lg fa-plus"></i> Add Player(s)</button>

    </div>
    <div class="col-xs-12 col-sm-6">
        <h2 class="text-center">Remove Player(s)</h2>
        <div class="list-group league-players manage-list">
            @if(count($leaguePlayers))
            @foreach($leaguePlayers as $lPlayer)
            <a id="member-{{{ $lPlayer->id }}}"class="list-group-item player-select">
                <h4>{{{ $lPlayer->user->fullname() }}}</h4>
            </a>
            @endforeach
            @else
            <a class="list-group-item">
                <h4>No league players</h4>
            </a>
            @endif
        </div>
        <button id="remove-players-btn" type="button" class="btn btn-danger col-xs-12"><i class="fa fa-fw fa-lg fa-times"></i> Remove Player(s)</button>
    </div>
</div>
@endsection

@section('page-scripts')
<script>
$('#remove-players-btn').hide();
$('#add-players-btn').hide();

$('.league-players .player-select').on('click touchstart', function (e) {
    e.preventDefault();

    if ($(this).hasClass('active')) {
        $(this).removeClass('active');
    } else {
        $(this).addClass('active');
    }

    if ($('.league-players .player-select.active').length > 0) {
        $('#remove-players-btn').fadeIn('fast');
    } else {
        $('#remove-players-btn').fadeOut('fast');
    }
});

$('#remove-players-btn').on('click touchstart', function (e) {
    e.preventDefault();

    var selectedPlayers = [];
    $('.league-players .player-select.active').each(function (i, item) {
        selectedPlayers.push(item.id.split('-')[1]);
    });

    $.ajax({
        url: '{{ route('league_manage_remove_league_players', [$league->slug]) }}',
        type: 'post',
        data: { '_token': '{{ csrf_token() }}', 'players': selectedPlayers },
        success: function (resp) {
            if (resp.status != 'error') {
                window.location.reload();
            } else {
                alert(resp.msg);
            }
        }
    });
});

$('.available-players .player-select').on('click touchstart', function (e) {
    e.preventDefault();

    if ($(this).hasClass('active')) {
        $(this).removeClass('active');
    } else {
        $(this).addClass('active');
    }

    if ($('.available-players .player-select.active').length > 0) {
        $('#add-players-btn').fadeIn('fast');
    } else {
        $('#add-players-btn').fadeOut('fast');
    }
});

$('#add-players-btn').on('click touchstart', function (e) {
    e.preventDefault();

    var selectedPlayers = [];
    $('.available-players .player-select.active').each(function (i, item) {
        selectedPlayers.push(item.id.split('-')[1]);
    });

    $.ajax({
        url: '{{ route('league_manage_add_league_players', [$league->slug]) }}',
        type: 'post',
        data: { '_token': '{{ csrf_token() }}', 'players': selectedPlayers },
        success: function (resp) {
            if (resp.status != 'error') {
                window.location.reload();
            } else {
                alert(resp.msg)
            }
        }
    });
});
</script>
@endsection
