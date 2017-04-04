@extends('app')

@section('content')
@include('page_header')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2>Youth Tournaments</h2>
    </div>
</div>
@include('youth.partials.header')
<div class="row tournaments">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8">
        <div class="list-group">
            @foreach($tournaments as $tournament)
            <a href="{{ route('tournament', [$tournament->name, $tournament->year]) }}" class="list-group-item">
                <h3>{{ $tournament->display_name }}</h3>
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
