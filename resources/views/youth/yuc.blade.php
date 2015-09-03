@extends('layouts.master')

@section('content')
@include('layouts.page_header')
<div class="row">
    <div class="col-xs-12 text-center">
        <img class="logo" src="{{ asset('img/yuc_logo.png') }}"/>
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
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8">
        {{ $page->content }}
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8">
        <h4>Leagues</h4>
        You may view the leagues <a href="{{ route('youth_leagues') }}">here</a>.
    </div>
</div>
@endsection

@section('page-scripts')
@endsection
