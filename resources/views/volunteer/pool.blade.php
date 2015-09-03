@extends('layouts.master')

@section('content')
@include('layouts.page_header')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Volunteer Base</h2>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8 text-justify">
        <p>
            You may download a list of all the information for the volunteers <a href="{{ route('volunteer_list_download') }}">here</a>.
        </p>
    </div>
</div>
@endsection
