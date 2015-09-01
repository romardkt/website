@extends('app')

@section('content')
    <div class="row home-page">
        <div class="col-xs-12 col-sm-8">
            @if(count($featured))
            <h3 class="heading youth">Featured News</h3>
            <div id="featured-news" class="carousel slide" data-ride="carousel">
                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                    @foreach($featured as $id => $feature)
                    <div class="item{{ ($id == 0) ? ' active': '' }}">
                        <img src="{{ $feature->image }}"/>
                        <div class="carousel-caption">
                            <a target="{{ (starts_with($feature->link, 'http')) ? '_new' : '' }}" href="{{ ($feature->link === null) ? route('post_view', [$feature->slug]) : url($feature->link) }}">
                                <h4>{{{ $feature->title }}}</h4>
                                <p class="hidden-xs">
                                    <small>
                                        posted at
                                        {{{ (new DateTime($feature->post_at))->format('M d Y @ h:i A') }}} by
                                        <strong>{{{ $feature->postedBy->fullname() }}}</strong>
                                    </small>
                                </p>
                                {{{ str_limit(strip_tags($feature->content), 125, '...') }}}
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Indicators -->
                <ol class="carousel-indicators">
                    @foreach($featured as $id => $feature)
                    <li data-target="#featured-news" data-slide-to="{{{ $id }}}" {{ ($id == 0) ? 'class="active"' : ''}}></li>
                    @endforeach
                </ol>
            </div>
            @endif
            <div class="row post">
                <div class="col-xs-12">

                    <h3 class="heading teams">
                        @if(count($featured))
                        More
                        @endif
                        News & Information
                    </h3>

                    @foreach($posts as $post)
                    <div class="list-group">
                        <a href="{{ ($post->link === null) ? route('post_view', [$post->slug]) : url($post->link) }}" class="list-group-item">
                            <h4>{{{ $post->title }}} <div class="badge {{{ $post->category }}}">{{{ ucwords($post->category) }}}</div></h4>
                            <p>
                                <small class="text-muted">
                                    posted on <strong>{{{ convertDate($post->post_at, 'M d Y @ h:i A') }}}</strong> by <strong>{{{ $post->postedBy->fullname() }}}</strong>
                                </small>
                            </p>
                        </a>
                        <div class="content">{{{ str_limit(strip_tags($post->content), 250, '...') }}}</div>
                    </div>
                    @endforeach

                    <a class="btn btn-default col-xs-12 all-news" href="{{ route('posts') }}">View All News</a>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4">
            <div class="row leagues">
                <div class="col-xs-12">
                    <h3 class="heading leagues">Leagues</h3>
                    <div class="list-group">
                        @foreach($leagues as $league)
                        <a href="{{route('league', $league->slug)}}" class="list-group-item">
                            <span class="badge {{{ ($league->is_youth) ? 'youth' : 'league' }}}">{{{ $league->status() }}}</span>
                            {{{ $league->displayName() }}}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4">
            <div class="row">
                <div class="col-xs-12">
                    <h3 class="heading about">Calendar</h3>
                    <iframe src="https://www.google.com/calendar/embed?showTitle=0&showNav=0&showDate=0&showPrint=0&showTabs=0&showCalendars=0&mode=AGENDA&height=250&wkst=1&bgcolor=%23FFFFFF&src=4fgn2m8vi3pqaupf3kkge3i1fg%40group.calendar.google.com&color=%23A32929&ctz=America%2FNew_York"
                        width="100%"
                        height="300"
                        frameborder="0"
                        scrolling="no">
                    </iframe>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4">
            <div class="row pickups">
                <div class="col-xs-12">
                    <h3 class="heading volunteer">Pickup Games</h3>
                    <div class="list-group">
                        @foreach($pickups as $pickup)
                        <a href="{{ route('around_pickups') }}" class="list-group-item">
                            <span class="badge volunteer">{{{ $pickup->day }}}</span>
                            {{{ $pickup->title }}}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 last">
            <div class="row tournaments">
                <div class="col-xs-12">
                    <h3 class="heading around">Tournaments</h3>
                    <?php $usOpen = false; ?>
                    @foreach($tournaments as $tournament)
                    <?php if ($tournament->end < (new DateTime())->format('Y-m-d')): continue; endif; ?>

                    @if($tournament->start < (new DateTime('2015-07-02'))->format('Y-m-d') && !$usOpen)
                    <?php $usOpen = true; ?>
                    <a href="http://play.usaultimate.org/events/US-Open-Ultimate-Championships-2015/" class="list-group-item">
                        <span class="badge around">{{{ (new DateTime('2015-07-02'))->format('M j Y') }}}</span>
                        US Open 2015
                    </a>
                    @endif
                    <a href="{{ route('tournament', [$tournament->name, $tournament->year]) }}" class="list-group-item">
                        <span class="badge around">{{{ (new DateTime($tournament->start))->format('M j Y') }}}</span>
                        {{{ $tournament->display_name }}}
                    </a>
                    @if($tournament->name == 'scinny' && in_array($tournament->year, [2014, 2015]))
                    <a href="{{ route('tournament_masters_' . $tournament->year) }}" class="list-group-item">
                        <span class="badge around">{{{ (new DateTime($tournament->start))->format('M j Y') }}}</span>
                        GL G/Masters Regionals {{ $tournament->year }}
                    </a>
                    @endif
                    @endforeach
                    @if(!$usOpen)
                    <a href="http://play.usaultimate.org/events/US-Open-Ultimate-Championships-2015/" class="list-group-item">
                        <span class="badge around">{{{ (new DateTime('2015-07-02'))->format('M j Y') }}}</span>
                        US Open 2015
                    </a>
                    @endif
                    <a class="list-group-item text-muted text-center" href="{{ route('around_tournaments') }}">Other/Past Tournaments</a>
                </div>
            </div>
        </div>
    </div>
@endsection
