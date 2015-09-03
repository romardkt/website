@extends('app')

@section('content')
@include('layouts.page_header')
<div class="row">
    <div class="col-xs-12">
        <a class="btn btn-default" href="{{ route('youth_clinics') }}"><i class="fa fa-lg fa-fw fa-chevron-circle-left"></i> All Clinics</a>
    </div>
</div>
<div class="row">
    {{ $clinic->content }}
</div>
@endsection

@section('page-scripts')
@endsection
