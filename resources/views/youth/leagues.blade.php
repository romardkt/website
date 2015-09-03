@extends('app')

@section('content')
@include('layouts.page_header')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Youth Ultimate Leagues</h2>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 text-right">
        <a class="twitter" href="https://twitter.com/yuctweets" target="_new" title="tw:@yuctweets">
            <span class="fa-stack fa-lg">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-twitter fa-stack-1x fa-inverse"></i>
            </span></a>
        <a class="facebook" href="https://www.facebook.com/yucpage" target="_new" title="fb:yucpage">
            <span class="fa-stack fa-lg">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
            </span></a>
    </div>
</div>
<div class="row leagues">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8">
        <div class="list-group">
            @foreach($leagues as $league)
            <a href="{{ route('league', [$league->slug]) }}" class="list-group-item{{{ ($league->is_archived) ? ' archived' : '' }}}{{{ ($league->date_visible === null || $league->date_visible > (new DateTime())->format('Y-m-d H:i:s')) ? ' not-visible' : '' }}}">
                <h4>{{{ $league->displayName() }}}</h4>
                @if($league->date_visible === null || $league->date_visible > (new DateTime())->format('Y-m-d H:i:s'))
                <strong class="pull-right text-warning">** Not Visible **</strong>
                @endif
                @if($league->is_archived)
                <strong class="pull-right text-danger">** Archived **</strong>
                @endif
                <div class="label label-info">{{{ $league->status() }}}</div>
                <p>{{ str_limit(preg_replace("/\s+/", ' ', strip_tags($league->description)), 250) }}</p>
            </a>
            @endforeach
        </div>
        <div class="pull-right">{{ $leagues->render() }}</div>
    </div>
</div>
@endsection

@section('page-scripts')
@endsection
