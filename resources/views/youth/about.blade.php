@extends('app')

@section('content')
@include('page_header')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Youth Ultimate</h2>
    </div>
</div>
@include('youth.partials.header')
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8">
        {!! $page->content !!}
    </div>
</div>
@endsection

@section('page-scripts')
@endsection
