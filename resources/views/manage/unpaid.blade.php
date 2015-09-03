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
    <div class="col-xs-12 col-sm-8">
        <legend>Unpaid List</legend>

        <div class="list-group">
            @foreach($unpaid as $player)
            <div class="list-group-item">
                <span class="badge">$ {{ $player->balance }}</span>
                <h4 class="list-group-item-heading">{{ $player->user->fullname() }}</h4>
                <p class="list-group-item-text">
                    @foreach(explode(',', $player->leagues) as $leagueId)
                    <p><a href="{{ route('league_status', [$leagues[$leagueId]['slug']]) }}">{{ $leagues[$leagueId]['name'] }} <span class="label label-danger">${{ $leagues[$leagueId]['cost'] }}</span></a></p>
                    @endforeach
                </p>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
