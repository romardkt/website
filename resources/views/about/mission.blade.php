@extends('app')

@section('content')
@include('page_header')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">{{ $page->display }}</h2>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8 text-justify">
        {!! $page->content !!}
    </div>
</div>
@endsection
