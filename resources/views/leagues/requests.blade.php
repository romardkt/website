@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">{{ $league->displayName() }} Team Requests</h2>
    </div>
</div>
@include('leagues.header')
@if(count($requests))
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <table class="requests table table-condensed table-hover table-responsive">
            <thead>
                <tr>
                    <th class="col-xs-3 col-sm-4">Player</th>
                    <th class="col-xs-3 col-sm-6">Team</th>
                    <th class="col-xs-3 col-sm-2">Registered</th>
                    <th class="col-xs-3 col-sm-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $player => $data)
                <tr id="request-{{{ $data['member'] }}}">
                    <td>{{{ $player }}}</td>
                    <td>{{{ $data['requested']['name'] }}}</td>
                    <td class="text-center">{{{ (new DateTime($data['registered_at']))->format('M j Y h:i A') }}}</td>
                    <td>
                        <a title="Assign to team" class="btn btn-success" href="{{ route('league_requests_accept', [$league->slug, $data['member']]) }}"><i class="fa fa-fw fa-lg fa-check"></i> Accept</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<h4 class="text-center">There are no requests pending right now</h4>
@endif
@endsection

@section('page-scripts')
<script>
$('.accept-btn').on('click touchstart', function (e) {
    e.preventDefault();
    var memberId = $(this).data('member');

    $.ajax({
        url: '/' + memberId ,
        type: 'post',
        data: { '_token': '{{ csrf_token() }}' },
        success: function (resp) {
            $('#request-' + memberId).fadeOut('fast');
            if (resp.status !== 'success') {
                $('#request-' + memberId).fadeIn('fast');
            }
        }
    });
});
</script>
@endsection
