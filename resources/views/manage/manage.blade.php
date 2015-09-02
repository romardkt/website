@extends('app')

@section('content')

<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <h2 class="page">CUPA Management</h2>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-2 text-center">
        @include('manage.menu')
    </div>
    <div class="col-xs-12 col-sm-8">
    </div>
</div>
@endsection
