@extends('app')

@section('content')
@include('page_header')
<div class="row">
    <div class="col-xs-12 text-center">
        <img class="logo" src="{{ asset('img/yuc_logo.png') }}"/>
    </div>
</div>
@include('youth.partials.header')
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8">
        {!! $page->content !!}
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
