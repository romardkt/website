@extends('app')

@section('content')
@include('layouts.page_header')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Youth Tournaments</h2>
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
<div class="row tournaments">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8">
        <div class="list-group">
            @foreach($tournaments as $tournament)
            <a href="{{ route('tournament', [$tournament->name, $tournament->year]) }}" class="list-group-item">
                <h3>{{{ $tournament->display_name }}}</h3>
                <em>{{ (new DateTime($tournament->start))->format('M j Y')}} -
                {{ (new DateTime($tournament->end))->format('M j Y')}}</em>
                <p>
                    {{ str_limit(preg_replace("/\s+/", ' ', strip_tags($tournament->description)), 350) }}
                </p>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
@endsection
