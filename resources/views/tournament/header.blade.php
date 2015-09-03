<div class="top-bar">
    <div class="col-xs-3 red"></div>
    <div class="col-xs-3 blue"></div>
    <div class="col-xs-3 green"></div>
    <div class="col-xs-3 yellow"></div>
</div>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <nav class="navbar navbar-inverse tournament-menu" role="navigation">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="{{ route('tournament', [$tournament->name, $tournament->year]) }}">
                            {{{ $tournament->display_name }}}<br/>
                            <small class="text-muted">{{{ convertDate($tournament->start, 'M j-') }}}{{{ convertDate($tournament->end, 'j ') }}}{{{ convertDate($tournament->end, 'Y') }}}</small>
                        </a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            @if($tournament->name == 'nationals' && $tournament->year == '2014')
                            <li{{ (Route::currentRouteName() == 'tournament_2014_nationals_fans') ? ' class="active"' : '' }}><a href="{{ route('tournament_2014_nationals_fans') }}">Parents/Fans</a></li>
                            @endif
                            @if($tournament->use_bid == 1)
                            <li{{ (Route::currentRouteName() == 'tournament_bid' || Route::currentRouteName() == 'tournament_payment') ? ' class="active"' : '' }}><a href="{{ route('tournament_bid', [$tournament->name, $tournament->year]) }}">Bid</a></li>
                            @elseif($tournament->use_paypal)
                            <li{{ (Route::currentRouteName() == 'tournament_payment') ? ' class="active"' : '' }}><a href="{{ route('tournament_payment', [$tournament->name, $tournament->year]) }}">Bid</a></li>
                            @endif
                            @if($tournament->has_teams == 1)
                            <li{{ (Route::currentRouteName() == 'tournament_teams') ? ' class="active"' : '' }}><a href="{{ route('tournament_teams', [$tournament->name, $tournament->year]) }}">Teams</a></li>
                            @endif
                            <?php $schedule = ((in_array($tournament->name, ['nationals', 'club_regionals'])) && $tournament->year == '2014') ? 'Teams/Schedule' : 'Schedule'; ?>
                            <li{{ (Route::currentRouteName() == 'tournament_schedule') ? ' class="active"' : '' }}><a href="{{ route('tournament_schedule', [$tournament->name, $tournament->year]) }}">{{{ $schedule }}}</a></li>
                            <li{{ (Route::currentRouteName() == 'tournament_location') ? ' class="active"' : '' }}><a href="{{ route('tournament_location', [$tournament->name, $tournament->year]) }}">Location</a></li>
                            <li{{ (Route::currentRouteName() == 'tournament_contact') ? ' class="active"' : '' }}><a href="{{ route('tournament_contact', [$tournament->name, $tournament->year]) }}">Contact</a></li>
                            @if($tournament->name == 'scinny' && in_array($tournament->year, ['2014', '2015']))
                            <li{{ (Route::currentRouteName() == 'tournament_masters_' . $tournament->year) ? ' class="active"' : '' }}><a href="{{ route('tournament_masters_' . $tournament->year) }}">Great Lakes G/Masters</a></li>
                            @endif
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            @if(!$isAuthorized['user'])
                            <li><a href="#" data-toggle="modal" data-target="#login">Login</a></li>
                            @endif
                            @if($isAuthorized['manager'])
                            <li{{ (Route::currentRouteName() == 'tournament_admin') ? ' class="active"' : '' }}><a href="{{ route('tournament_admin', [$tournament->name, $tournament->year]) }}">Admin</a></li>
                            @endif
                            <li><a href="{{ route('home') }}">CUPA Home</a></li>
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav>
        </div>
    </div>
</div>
<div class="container">
    <div class="row tournament-banner">
        <img src="{{ asset($tournament->image) }}"/>
        <div class="actions">
        @if($isAuthorized['manager'])
        @if(Route::currentRouteName() == 'tournament')
            <a class="btn btn-default" href="{{ route('tournament_feed_add', [$tournament->name, $tournament->year]) }}"><i class="fa fa-fw fa-lg fa-plus"></i> Add News</a>
        @elseif(Route::currentRouteName() == 'tournament_contact')
            <a class="btn btn-default" href="{{ route('tournament_contact_add', [$tournament->name, $tournament->year]) }}"><i class="fa fa-fw fa-lg fa-plus"></i> Add Contact</a>
        @elseif(Route::currentRouteName() == 'tournament_location')
            <div class="btn-group">
                <a class="btn btn-default" href="{{ route('tournament_location_map_edit', [$tournament->name, $tournament->year]) }}"><i class="fa fa-fw fa-lg fa-edit"></i> Edit Location</a>
                <a class="btn btn-default" href="{{ route('tournament_location_add', [$tournament->name, $tournament->year]) }}"><i class="fa fa-fw fa-lg fa-plus"></i> Add Lodging</a>
            </div>
        @elseif(Route::currentRouteName() == 'tournament_teams')
            <a class="btn btn-default" href="{{ route('tournament_teams_add', [$tournament->name, $tournament->year]) }}"><i class="fa fa-fw fa-lg fa-plus"></i> Add Team</a>
        @elseif(Route::currentRouteName() == 'tournament_bid' || Route::currentRouteName() == 'tournament_payment')
            <a class="btn btn-default" href="{{ route('tournament_bid_edit', [$tournament->name, $tournament->year]) }}"><i class="fa fa-fw fa-lg fa-edit"></i>Edit Bid</a>
        @else
            <a class="btn btn-default placeholder">&nbsp;</a>
        @endif
        @else
            <a class="btn btn-default placeholder">&nbsp;</a>
        @endif
        </div>
    </div>
</div>
<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="loginLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">CUPA User Login</h4>
            </div>
            <div class="modal-body">
                <div id="login-error"></div>
                {{ Form::open(['id' => 'login-form', 'class' => 'form form-horizontal', 'role' => 'form']) }}
                    @include('layouts.partials.login')
                {{ Form::close() }}
            </div>
            <div class="modal-footer hidden-xs">
                <a class="btn btn-warning pull-left" href="{{ route('register') }}">Don't have an account?</a>
                <a class="btn btn-info pull-left" href="{{ route('reset') }}">Reset Password</a>
                <button type="button" class="btn btn-default" data-dismiss="modal"> &nbsp; &nbsp; Cancel &nbsp; &nbsp; </button>
                <button type="button" class="btn btn-primary login-form-submit"> &nbsp; &nbsp; &nbsp;Log In &nbsp; &nbsp; &nbsp;</button>
            </div>
            <div class="modal-footer visible-xs text-center">
                <div class="row actions">
                    <button type="button" class="btn btn-primary login-form-submit">Log In</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-warning" href="{{ route('register') }}">Don't have an account?</a>
                    <a class="btn btn-info" href="{{ route('reset') }}">Reset Password</a>
                </div>
            </div>
        </div>
    </div>
</div>
