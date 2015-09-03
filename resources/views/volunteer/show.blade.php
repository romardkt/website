@extends('app')

@section('content')
@include('layouts.page_header')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Volunteer Opportunities</h2>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-xs-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
        <ul class="list-group volunteer-event">
            @if(count($events))
            @foreach($events as $event)
                <li class="list-group-item">
                    <span class="badge">
                        <div class="month">{{ date('M', strtotime($event->start)) }}</div>
                        <div class="day">{{ date('d', strtotime($event->start)) }}</div>
                        <div class="year">{{ date('Y', strtotime($event->start)) }}</div>
                        @if($isAuthorized['user'])
                        <div class="action"><a class="btn btn-success btn-xs" href="{{ route('volunteer_show_signup', array($event->id)) }}">Sign Up!</a></div>
                        @else
                        <div class="action"><a class="btn btn-success btn-xs" data-toggle="modal" data-target="#login" title="Login">Login to<br/> Sign Up!</a></div>
                        @endif
                        @if($isAuthorized['volunteer'] || (isset($isAuthorized['userData']) && $event->isContact($isAuthorized['userData']->id)))
                        <div class="action"><a class="btn btn-default btn-xs" href="{{ route('volunteer_show_members', array($event->id)) }}">Volunteers</a></div>
                        @endif
                    </span>
                    <h4 class="list-group-item-heading text-primary">
                        {{{ $event->title }}}
                        @if($isAuthorized['volunteer'] || (isset($isAuthorized['userData']) && $event->isContact($isAuthorized['userData']->id)))
                        <div class="pull-right edit-button">
                            <a class="btn btn-default" href="{{ route('volunteer_show_edit', [$event->id]) }}">
                                <i class="fa fa-edit fa-fw fa-lg"></i>
                            </a>
                        </div>
                        @endif
                    </h4>
                    <p class="list-group-item-text">
                        <p>
                            <strong>{{{ $event->needed() }}}</strong> more volunteers are needed<br/>
                            @if(substr($event->start, 0, 10) == substr($event->end, 0, 10))
                                At <strong>{{ date('h:i A', strtotime($event->start)) }}</strong> - <strong>{{ date('h:i A', strtotime($event->end)) }}</strong> on <strong>{{ date('F d Y', strtotime($event->end)) }}</strong>
                            @else
                                From <strong>{{ date('F d Y h:i A', strtotime($event->start)) }}</strong> to <strong>{{ date('F d Y h:i A', strtotime($event->end)) }}</strong>
                            @endif<br/>
                            <strong>Contact(s):</strong>
                            @foreach ($event->contacts as $contact) {{ secureEmail(($event->email_override === null) ? $contact->user->email : $event->email_override, $contact->user->fullname()) }}
                            @endforeach
                            <br/>
                            <strong>Location:</strong> <a target="_blank" href="{{ $event->location->getUrl() }}">{{ $event->location->name }}</a><br/>
                            <hr/>
                        </p>
                        <p>
                            {{ $event->information }}
                        </p>
                    </p>
                </li>
            @endforeach
            @else
            <div class="list-group-item">
                <h4>No opportunities available right now, check back soon.</h4>
            </div>
            @endif
        </ul>
    </div>
</div>
@endsection

@section('page-scripts')
@endsection
