@extends('tournament')

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <h1 class="title">{{{ $tournament->display_name }}} Bid Payment</h1>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @if($tournament->use_paypal == 1)
            @if(empty($tournament->paypal))
        <p>
            If you would like to pay via paypal you may do so below.  Just click the button and it will take you to paypal's site to pay for the tournament.
        </p>
        <p>
            The tournament cost is <span class="text-info">${{{ $tournament->cost }}}</span>
        </p>
                @if(count($teams))
        <div class="pull-left">
            <select id="team" class="form-control">
                <option value="0">Select a Team</option>
                @foreach($teams as $division => $data)
                <optgroup label="{{{ $division }}}">
                    @foreach($data as $team)
                    <option value="{{{ $team->id }}}">{{{ $team->name }}}</option>
                    @endforeach
                </optgroup>
                @endforeach
            </select>
        </div>
        &nbsp; <a id="paypal-btn" class="btn btn-success disabled" onclick="return pay();" href="{{ route('paypal', [$tournament->id, 'tournament']) }}">Pay via Paypal</a>
                @else
        <p><strong>There are currently no teams to pay for.</strong></p>
                @endif
            @else
        <pre>{!! $tournament->paypal !!}</pre>
            @endif
        <hr/>
        @endif
        @if(!empty($tournament->mail))
        <p>
            If you would like to mail a check in for the payment for the tournament you may mail payments to here:
        </p>
        <pre>{{ $tournament->mail }}</pre>

        @endif
    </div>
</div>
@endsection

@section('page-scripts')
<script>
$('#team').on('change', function () {
    if ($(this).val() != 0) {
        $('#paypal-btn').removeClass('disabled');
    } else {
        $('#paypal-btn').addClass('disabled');
    }
});

function pay()
{
    if ($('#team').val() == 0) {
        alert('You must select a team to pay for.');

        return false;
    }

    var href = $('#paypal-btn').prop('href');
    $('#paypal-btn').prop('href', href + '/0/' + $('#team').val());

    return true;
}

</script>
@endsection
