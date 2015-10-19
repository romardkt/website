@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <div class="pull-right">
            <a class="btn btn-default" href="{{ route('leagues_dayton_edit') }}">Edit</a>
        </div>
        <h2 class="page">{{ $page->display }}</h2>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8 text-justify">
        {!! $page->content !!}
    </div>
</div>
@endsection
