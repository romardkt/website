@extends('app')

@section('content')
@include('page_header')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Youth Ultimate Clinics</h2>
    </div>
</div>
@include('youth.partials.header')
<div class="row clinics">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8">
        <div class="list-group">
            @foreach($clinics as $clinic)
            <a href="{{ route('youth_clinic', [$clinic->name]) }}" class="list-group-item">
                <h4>{{ $clinic->display }}</h4>
                <div class="label label-info">{{ $clinic->type }}</div>
                <p>{!! str_limit(preg_replace("/\s+/", ' ', strip_tags($clinic->content)), 250) !!}</p>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
@endsection
