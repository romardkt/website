<div class="row footer-links">
    <div class="col-xs-12 text-center hidden-xs">
        <a href="{{ route('tournament_bid', [$tournament->name, $tournament->year]) }}">Bid</a> |
        <a href="{{ route('tournament_teams', [$tournament->name, $tournament->year]) }}">Teams</a> |
        <a href="{{ route('tournament_schedule', [$tournament->name, $tournament->year]) }}">Schedule</a> |
        <a href="{{ route('tournament_location', [$tournament->name, $tournament->year]) }}">Location</a> |
        <a href="{{ route('tournament_contact', [$tournament->name, $tournament->year]) }}">Contact</a> |
        @if(!$isAuthorized['user'])
        <a href="#" data-toggle="modal" data-target="#login">Login</a> |
        @endif
        @if($isAuthorized['manager'])
        <a href="{{ route('tournament_admin', [$tournament->name, $tournament->year]) }}">Admin</a> |
        @endif
        <a href="{{ route('home') }}">CUPA Home</a>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 text-center">
        <p>&copy; 2010-{{ date('Y') }} Cinctinnati Ultimate Players Assocaiation</p>
    </div>
</div>
