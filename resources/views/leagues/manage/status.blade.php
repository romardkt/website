@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">{{ $league->displayName() }} Wavier/Paid Status</h2>
    </div>
</div>
@include('leagues.header')
<div class="row">
    <div class="col-xs-6">
        <div class="btn-group">
            <a class="btn btn-default{{{ ($all === false) ? '' : ' active' }}}" href="{{ route('league_status', [$league->slug, 'all']) }}">All Players</a>
            <a class="btn btn-default{{{ ($all === false) ? ' active' : '' }}}" href="{{ route('league_status', [$league->slug]) }}">Outstanding Players</a>
        </div>
    </div>
    <div class="col-xs-6 text-right">
        <div class="btn-group">
            <a class="btn btn-default" href="{{ route('league_status', [$league->slug, 'all']) }}?print=true"><i class="fa fa-fw fa-lg fa-print"></i> Printable</a>
            <a class="btn btn-default" href="{{ route('league_status_download', [$league->slug, ($all === false) ? 'outstanding' : 'all']) }}"><i class="fa fa-fw fa-lg fa-list"></i> Export</a>
        </div>
    </div>
</div>
<hr/>@if(count($statuses))
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <table class="statuses table table-condensed table-hover table-responsive">
            <thead>
                <tr>
                    <th class="col-xs-6">Player</th>
                    <th class="col-xs-2 text-center">Paid</th>
                    <th class="col-xs-2 text-center">Waiver</th>
                    <th class="col-xs-2 text-center">Balance</th>
                </tr>
            </thead>
            <tbody>
                @foreach($statuses as $status)
                <tr id="status-{{{ $status['id'] }}}"{{ ($status['balance'] > 0) ? ' class="danger"' : '' }}>
                    <td>
                      {{{ $status['first_name'] . ' '  . $status['last_name'] }}}
                      @if($status['waiver'] !== null)
                        <a rel="noopener noreferrer" target="_blank" href="{{route('waiver_export', [$league->year, $status['user_id']])}}"><i class="fa fa-fw fa-download" title="Download waiver"></i></a>
                      @endif
                    </td>
                    <td class="text-center">
                        @if($status['paid'] == 1)
                        <button title="Mark as NOT paid" type="button" class="btn btn-success accept-btn" data-member="{{{ $status['id'] }}}"  data-type="paid"><i class="fa fa-fw fa-lg fa-check"></i></button>
                        @else
                        <button title="Mark as paid" type="button" class="btn btn-danger accept-btn" data-member="{{{ $status['id'] }}}"  data-type="paid"><i class="fa fa-fw fa-lg fa-times"></i></button>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($status['waiver'] !== null)
                        <button title="Check off waiver" type="button" class="btn btn-success accept-btn" data-member="{{{ $status['id'] }}}"  data-type="waiver"><i class="fa fa-fw fa-lg fa-check"></i></button>
                        @else
                        <button title="Uncheck waiver" type="button" class="btn btn-danger accept-btn" data-member="{{{ $status['id'] }}}"  data-type="waiver"><i class="fa fa-fw fa-lg fa-times"></i></button>
                        @endif
                    </td>
                    <td class="text-center balance">
                        {{{ $status['balance'] }}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<h4 class="text-center">There are no statuses pending right now</h4>
@endif
@endsection

@section('page-scripts')
<script>
$('.accept-btn').on('click touchstart', function (e) {
    e.preventDefault();
    var memberId = $(this).data('member');
    var type = $(this).data('type');
    var hideStuff = {{{ ($all === false) ? 1 : 0 }}};
    var button = $(this);

    $.ajax({
        url: '{{ route('league_status_toggle', [$league->slug]) }}',
        type: 'post',
        data: { '_token': '{{ csrf_token() }}', 'member': memberId, 'type': type },
        success: function (resp) {
            if (resp.status == 'success') {
                if ((resp.paid == 1 && resp.waiver == 1 && resp.balance < 1) && hideStuff == 1) {
                    $('#status-' + memberId).fadeOut('fast');
                } else {
                    if (button.hasClass('btn-success')) {
                        button.removeClass('btn-success');
                        button.addClass('btn-danger');
                        button.children('i').removeClass('fa-check');
                        button.children('i').addClass('fa-times');
                        $('#status-' + memberId).children('.balance').html(resp.balance);
                        if (type == 'paid') {
                            $('#status-' + memberId).addClass('danger');
                        }
                    } else {
                        button.removeClass('btn-danger');
                        button.addClass('btn-success');
                        button.children('i').removeClass('fa-times');
                        button.children('i').addClass('fa-check');
                        $('#status-' + memberId).children('.balance').html(resp.balance);
                        if (type == 'paid' && resp.balance < 1) {
                            $('#status-' + memberId).removeClass('danger');
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
