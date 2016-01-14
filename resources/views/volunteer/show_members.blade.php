@extends('app')

@section('content')
@include('page_header')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">{{{ $event->title }}} Signups</h2>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 text-center">
        <div class="btn-group">
            @if($past)
            <a class="btn btn-default" href="{{ route('volunteer_show_past') }}"><i class="fa fa-lg fa-arrow-circle-left"></i> Back To Events</a>
            @else
            <a class="btn btn-default" href="{{ route('volunteer_show') }}"><i class="fa fa-lg fa-arrow-circle-left"></i> Back To Events</a>
            @endif
            <a class="btn btn-default" href="{{ route('volunteer_show_members_export', array($event->id)) }}"><i class="fa fa-lg fa-list"></i> Export Data</a>
        </div>
        <p>
            You may export data to see all of the answers to the signup questions.
        </p>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Volunteer</th>
                    <th class="hidden-xs">Email</th>
                    <th class="hidden-xs col-xs-2">Phone</th>
                    <th class="text-center">CUPA Involvement</th>
                    <th class="hidden-xs hidden-sm text-center col-xs-2">Past CUPA Experience</th>
                    <th class="hidden-xs hidden-sm text-center col-xs-3">Primary Interests</th>
                </tr>
            </thead>
            <tbody>
                @foreach($event->members as $member)
                <tr>
                    <td>{{ $member->volunteer->user->fullname() }}</td>
                    <td class="hidden-xs">{{ $member->volunteer->user->email }}</td>
                    <td class="hidden-xs">{{ $member->volunteer->user->profile->phone }}</td>
                    <td class="text-center">{{ $member->volunteer->involvement }}</td>
                    <td class="hidden-xs hidden-sm">{{ $member->volunteer->experience }}</td>
                    <td class="hidden-xs hidden-sm">{{ $member->volunteer->primary_interest }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<br/>
@endsection

@section('page-scripts')
@endsection
