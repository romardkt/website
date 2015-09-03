@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        @if($isAuthorized['hoy_scholarship'])
        <div class="pull-right">
            <a class="btn btn-default" href="{{ route('scholarship_hoy_manage') }}">Submissions</a>
            <a class="btn btn-default" href="{{ route('scholarship_hoy_edit') }}">Edit</a>
        </div>
        @endif
        <h2 class="page">{{ $page->display }}</h2>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8 text-justify">
        {{ $page->content }}
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8 text-center">
        <p>
            <br/>
            <a class="btn btn-default" href="{{ route('scholarship_hoy_submit') }}">Submit a request for scholarship</a>
            <br/>
        </p>
    </div>
</div>
@endsection
