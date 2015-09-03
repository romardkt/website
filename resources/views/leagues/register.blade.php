@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">{{ $league->displayName() }} {{{ ucfirst($type) }}}</h2>
    </div>
</div>
<hr/>
@include('leagues.register_menu')
@include('leagues.register_' . $state)
@endsection

@section('page-scripts')
@endsection
