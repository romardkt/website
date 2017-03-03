<div class="top-bar">
    <div class="col-xs-3 red"></div>
    <div class="col-xs-3 blue"></div>
    <div class="col-xs-3 green"></div>
    <div class="col-xs-3 yellow"></div>
</div>
<div class="row header">
    <div class="container">
        <div class="row mobile-header">
            <div class="col-xs-3">
                <button type="button" id="mobile-main-menu-btn" class="btn"><i class="fa fa-lg fa-3x fa-bars"></i></button>
            </div>
            <div class="col-xs-6 logo text-center">
                <a href="{{ route('home') }}"><img src="{{ asset('img/logo.png') }}" alt="CUPA logo"></a>
            </div>
            <div class="col-xs-3 text-right">
                <button type="button" id="mobile-user-menu-btn" class="btn"><i class="fa fa-lg fa-3x fa-user"></i></button>
            </div>
        </div>
        <div class="row normal-header">
            <div class="col-sm-6 logo">
                <a href="{{ route('home') }}"><img src="{{ asset('img/logo.png') }}" alt="CUPA logo"></a>
            </div>
            <div class="col-sm-6">
                <div class="row text-right login">
                    <ul class="nav links pull-right">
                        @can('is-manager')
                        <li><a href="{{ route('manage') }}" title="News Posts">Manage</a></li>
                        @endif
                        <li><a href="{{ route('posts') }}" title="News Posts">All News</a></li>
                        <li><a href="{{ route('contact') }}" title="Contact Us">Contact Us</a></li>
                        @if(Auth::check())
                        <li><a id="user-menu-btn" href="#" title="Profile">{{ (Session::has('admin_user')) ? 'Impersonating ' : '' }}{{ Auth::user()->fullname() }}</a></li>
                        @else
                        <li><a href="{{ route('register') }}" title="Sign Up">Sign Up</a></li>
                        <li><a href="#" data-toggle="modal" data-target="#login" title="Login">Login</a></li>
                        @endif
                    </ul>
                </div>
                <div class="row text-right social">
                    <a class="twitter" href="https://twitter.com/cincyultimate" target="_new" title="tw:@cincyultimate">
                        <span class="fa-stack fa-lg">
                            <i class="fa fa-circle fa-stack-2x"></i>
                            <i class="fa fa-twitter fa-stack-1x fa-inverse"></i>
                        </span></a>
                    <a class="facebook" href="https://www.facebook.com/cincyultimate" target="_new" title="fb:cincyultimate">
                        <span class="fa-stack fa-lg">
                            <i class="fa fa-circle fa-stack-2x"></i>
                            <i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
                        </span></a>
                </div>
            </div>
            <div class="row main-menu">
                <div class="col-xs-12">
                    <div class="btn-group btn-group-lg btn-group-justified">
                        <a class="about btn btn-default" href="{{ route('about') }}">About<span class="hidden-sm"> Us</span></a></a>
                        <a class="volunteer btn btn-default" href="{{ route('volunteer') }}">Volunteer</a></a>
                        <a class="youth btn btn-default" href="{{ route('youth') }}">Youth<span class="hidden-sm"> Ultimate</span></a></a>
                        <a class="leagues btn btn-default" href="{{ route('leagues') }}"><span class="hidden-sm">Adult </span>Leagues</a></a>
                        <a class="around btn btn-default" href="{{ route('around') }}">Around<span class="hidden-sm"> Town</span></a></a>
                        <a class="teams btn btn-default" href="{{ route('teams') }}"><span class="hidden-sm">Area </span>Teams</a></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="mobile-main-menu" class="row mobile-main-menu" role="navigation">
    <div class="col-xs-12">
        <ul class="nav">
            @can('is-manager')
            <li><a href="{{ route('manage') }}" title="News Posts">Manage</a></li>
            @endif
            <li><a class="about" href="{{ route('about') }}">About Us</a></li>
            <li><a class="volunteer" href="{{ route('volunteer') }}">Volunteer</a></li>
            <li><a class="youth" href="{{ route('youth') }}">Youth Ultimate</a></li>
            <li><a class="leagues" href="{{ route('leagues') }}">Adult Leagues</a></li>
            <li><a class="around" href="{{ route('around') }}">Around Town</a></li>
            <li><a class="teams" href="{{ route('teams') }}">Area Teams</a></li>
        </ul>
    </div>
</div>

<div id="mobile-user-menu" class="mobile-user-menu" role="navigation">
    @if(Auth::user())
    <div class="row user-header">
        <div class="col-xs-5 avatar">
            <img src="{{ asset(Auth::user()->avatar) }}" alt="User avatar"/>
        </div>
        <div class="col-xs-7 user">
            <p class="name">
                {{ Auth::user()->first_name }}<br/>
                {{ Auth::user()->last_name }}<br/>
                <span class="text-muted">{{ Auth::user()->profile->nickname }}</span>
            </p>
        </div>
    </div>
    <div class="row status">
        <div class="col-xs-4 text-center">
            Waiver<br/>
            @if(Auth::user()->hasWaiver())
            <span class="label label-success">YES</span>
            @else
            <span class="label label-danger"><a href="{{ route('waiver', [date('Y'), Auth::id()]) }}">NO</a></span>
            @endif
        </div>
        <div class="col-xs-4 text-center">
            Profile<br/>
            @if(Auth::user()->profileComplete())
            <span class="label label-success">Complete</span>
            @else
            <span class="label label-danger"><a href="{{ route('profile') }}">Incomplete</a></span>
            @endif
        </div>
        <div class="col-xs-4 text-center">
            Overdue<br/>
            @if(Auth::user()->balance())
            <span class="label label-danger"><a href="{{ route('profile_leagues') }}">${{ Auth::user()->balance() }}</a></span>
            @else
            <span class="label label-success">$0</span>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <ul class="nav">
                <li><a href="{{ route('profile') }}"><i class="fa fa-fw fa-info-circle"></i>Personal Information</a></li>
                <li><a href="{{ route('profile_coaching') }}"><i class="fa fa-fw fa-graduation-cap"></i> Coaching Requirements</a></li>
                <li><a href="{{ route('manage_waivers') }}"><i class="fa fa-fw fa-graduation-cap"></i> Waivers/Releases</a></li>
                <li><a href="{{ route('profile_leagues') }}"><i class="fa fa-fw fa-group"></i> Leagues</a></li>
                <li><a href="{{ route('profile_contacts') }}"><i class="fa fa-fw fa-exclamation-triangle"></i> Emergency Contacts</a></li>
                <li><a href="{{ route('profile_password') }}"><i class="fa fa-fw fa-lock"></i> Change Password</a></li>
                <li><a id="logout-link" href="#"><i class="fa fa-fw fa-sign-out"></i> Logout</a></li>
            </ul>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-xs-12">
            <ul class="nav">
                <li><a href="#" data-toggle="modal" data-target="#login"><i class="fa fa-fw fa-user"></i> Login / Sign-up</a></li>
                <li><a href="{{ route('profile') }}"><i class="fa fa-fw fa-question"></i> Forgot Password</a></li>
                <li><a href="{{ route('contact') }}"><i class="fa fa-fw fa-envelope"></i> Contact Us</a></li>
            </ul>
        </div>
    </div>
    @endif
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
                {!! Form::open(['id' => 'login-form', 'class' => 'form form-horizontal', 'role' => 'form']) !!}
                    @include('partials.login')
                {!! Form::close() !!}
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
