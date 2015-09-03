@extends('app')

@section('content')
@include('layouts.page_header')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Youth Ultimate Clinics</h2>
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
<div class="row clinics">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8">
        <div class="list-group">
            @foreach($clinics as $clinic)
            <a href="{{ route('youth_clinic', [$clinic->name]) }}" class="list-group-item">
                <h4>{{{ $clinic->display }}}</h4>
                <div class="label label-info">{{{ $clinic->type }}}</div>
                <p>{{ str_limit(preg_replace("/\s+/", ' ', strip_tags($clinic->content)), 250) }} }}</p>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
@endsection
