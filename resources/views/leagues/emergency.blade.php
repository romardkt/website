@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">{{ $league->displayName() }} Emergency Contacts</h2>
    </div>
</div>
@include('leagues.header')
@if(count($contacts))
@if($isAuthorized['manager'])
<div class="row">
    <div class="col-xs-12 text-right">
        <div class="btn-group">
            <a class="btn btn-default" href="{{ route('league_emergency_download', [$league->slug]) }}"><i class="fa fa-fw fa-lg fa-list"></i> Export</a>
        </div>
    </div>
</div>
<hr/>
@endif
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <table class="contacts table table-condensed table-hover table-responsive">
            <thead>
                <tr>
                    <th class="col-xs-2">Player</th>
                    <th class="col-xs-10">Contacts</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contacts as $player => $data)
                <tr>
                    <td>{{{ $player }}}</td>
                    <td>
                    @foreach ($data as $contact) {{{ $contact['name'] . ' (' . $contact['phone'] . ')' }}}<br/>
                    @endforeach
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<h4 class="text-center">There are no contacts right now</h4>
@endif
@endsection

@section('page-scripts')
@endsection
