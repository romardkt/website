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
        <legend>Cupa Coaches List</legend>
        <div class="row text-right">
            <div class="col-xs-12">
                <a class="btn btn-default" href="{{ route('manage_coaches_download') }}"><i class="fa fa-lg fa-fw fa-list"></i> Export Coaches</a>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-xs-12">
                <div class="list-group">
                    @foreach($coaches as $coach)
                    <div class="list-group-item">
                        <span class="badge">{{{ $coach['email'] }}}</span>
                        <h4 class="list-group-item-heading">{{{ $coach['name'] }}}</h4>
                        <p class="list-group-item-text">
                            <span class="text-muted">{{{ $coach['teams'] }}}</span>
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
