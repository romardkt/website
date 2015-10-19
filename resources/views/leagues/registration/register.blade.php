@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">{{ $league->displayName() }} {{{ ucfirst($type) }}}</h2>
    </div>
</div>
<hr/>
@include('leagues.registration.register_menu')
@include('leagues.registration.register_' . $state)
@endsection

@section('page-scripts')
@endsection
