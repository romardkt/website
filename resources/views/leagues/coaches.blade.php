@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">{{ $league->displayName() }} Coaches</h2>
    </div>
</div>
@include('leagues.header')
@can('is-manager')
<div class="row">
    <div class="col-xs-12 text-right">
        <div class="btn-group">
            <a class="btn btn-default" href="{{ route('league_coaches_email', [$league->slug]) }}"><i class="fa fa-fw fa-lg fa-envelope"></i> Email Incomplete Coaches</a>
            <!--<a class="btn btn-default" href="{{ route('league_coaches_download', [$league->slug]) }}"><i class="fa fa-fw fa-lg fa-list"></i> Export</a>-->
        </div>
    </div>
</div>
<hr/>
@endif
<div class="row">
    <div class="col-xs-12">
        <table class="table table-condensed">
            <thead>
                <tr>
                    <th class="col-xs-3">Coach</th>
                    <th class="col-xs-2 hidden-xs">Type</th>
                    <th class="col-xs-3">Team</th>
                    <th class="col-xs-2">Status</th>
                    <th class="col-xs-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($coaches as $coach)
                <?php $status = $coach->getStatus(); ?>
                <tr class="{{{ ($status['status'] == 'text-danger') ? str_replace('text-', '', $status['status']) : '' }}}">
                    <td>{{{ $coach->user->fullname() }}}</td>
                    <td class="hidden-xs">{{{ ucwords(str_replace('_', ' ', $coach->position)) }}}</td>
                    <td>{{{ $coach->team_name }}}</td>
                    <td class="{{{ $status['status'] }}}"><strong>{{{ $status['msg'] }}}</strong></td>
                    <td class="text-center">

                        @if(Gate::allows('edit', $league) || (Gate::allows('coach', $league) && $coach->user_id == Auth::id()))
                        <a class="btn btn-default" href="{{ route('league_coaches_edit', [$league->slug, $coach->id])}}"><i class="fa fa-lg fa-fw fa-edit"></i> Edit</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('page-scripts')
@endsection
