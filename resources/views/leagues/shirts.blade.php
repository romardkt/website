@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">{{ $league->displayName() }} Shirts</h2>
    </div>
</div>
@include('leagues.header')
@if(isset(array_slice($data, 0, 1)[0]))
<div class="row">
    <div class="col-xs-12 text-right">
        <div class="btn-group">
            <a class="btn btn-default" href="{{ route('league_shirts_download', [$league->slug]) }}"><i class="fa fa-fw fa-lg fa-list"></i> Export</a>
        </div>
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-xs-12">
        <table class="shirts table table-condensed table-hover table-responsive">
            <thead>
                <tr>
                    <th>Color</th>
                    @foreach(array_slice($data, 0, 1)[0]['sizes'] as $abbr => $counts)
                    <th>{{{ ucfirst($abbr) }}}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($data as $sizes)
                <tr>
                    <td class="color" style="color: {{{ $sizes['code'] }}};">{{{ ucwords($sizes['color']) }}}</td>
                    @foreach($sizes['sizes'] as $counts)
                    <td class="count">
                        @if($counts == 0)
                            <span class="badge">0</span>
                        @else
                            <span class="label label-primary"><strong>{{{ $counts }}}</strong></span>
                        @endif

                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<h4 class="text-center">There are no teams created yet</h4>
@endif
@endsection

@section('page-scripts')
@endsection
