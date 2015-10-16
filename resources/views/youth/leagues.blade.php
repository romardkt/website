@extends('app')

@section('content')
@include('page_header')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Youth Ultimate Leagues</h2>
    </div>
</div>
@include('youth.partials.header')
<div class="row leagues">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8">
        <div class="list-group">
            @foreach($leagues as $league)
            <a href="{{ route('league', [$league->slug]) }}" class="list-group-item{{ ($league->is_archived) ? ' archived' : '' }}{{ ($league->date_visible === null || $league->date_visible > (new DateTime())->format('Y-m-d H:i:s')) ? ' not-visible' : '' }}">
                <h4>{{ $league->displayName() }}</h4>
                @if($league->date_visible === null || $league->date_visible > (new DateTime())->format('Y-m-d H:i:s'))
                <strong class="pull-right text-warning">** Not Visible **</strong>
                @endif
                @if($league->is_archived)
                <strong class="pull-right text-danger">** Archived **</strong>
                @endif
                <div class="label label-info">{{ $league->status() }}</div>
                <p>{{ str_limit(preg_replace("/\s+/", ' ', strip_tags($league->description)), 250) }}</p>
            </a>
            @endforeach
        </div>
        <div class="pull-right">{!! $leagues->render() !!}</div>
    </div>
</div>
@endsection

@section('page-scripts')
@endsection
