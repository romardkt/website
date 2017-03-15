@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">{{ $league->displayName() }}<br/>Registration/Waitlist Successful</h2>
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <h3>Players registered or waitlisted</h3>
        <p>Click the 'No' for waiver status to sign a waiver</p>
        <a class="btn btn-primary" href="{{ route('league_register', [$league->slug]) }}">Register another player</a>
        <table class="table table-condensed">
            <thead>
                <tr>
                    <th class="col-xs-6">Player</th>
                    <th class="col-xs-2 text-center">Status</th>
                    <th class="col-xs-2 text-center">Waiver</th>
                    <th class="col-xs-2 text-center">Paid</th>
                </tr>
            </thead>
            <tbody>
                <?php $playerOptions = [0 => 'Select a Player']; ?>
                @foreach($players as $player)
                @if($player->paid == 0 && ($player->position == 'player' || $player->position == 'waitlist' && $league->default_waitlist))
                <?php $playerOptions[$player->id] = $player->user->fullname(); ?>
                @endif
                <tr>
                    <td class="col-xs-6">{{{ $player->user->fullname() }}}</td>
                    <td class="col-xs-2 text-center">{!! ($player->position == 'player') ? '<span class="label label-success active">Registered</span>' : '<span class="label label-info active">Waitlisted</span>' !!}</td>
                    <td class="col-xs-2 text-center">{!! ($player->user->hasWaiver($league->year)) ? '<span class="label label-success active">Signed</span>' : '<a href="' . route('waiver', [$league->year, $player->user->id]) . '" class="label label-danger">Sign Waiver</a>' !!}</td>
                    <td class="col-xs-2 text-center">{!! ($player->paid) ? '<span class="label label-success active">Yes</span>' : '<span class="label label-danger active">No</span>' !!}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <h3>Payment Information</h3>
        <p>
            The cost of the league is:
            <strong class="text-info">
                {{{ ($league->registration->cost === null) ? 'Free' : '$ ' . $league->registration->cost }}}
                @if($league->registration->cost_female !== null)
                <span class="text-muted">( {{ ($league->registration->cost_female > 0) ? '$' . $league->registration->cost_female: 'Free' }} for female players )</span>
                @endif
            </strong>
        </p>
        @if($league->registration->cost !== null)
        <p>
            If you are not paying online, bring your payment with you on the first night or you risk being removed from the league.
        </p>
            @if(count($playerOptions) > 1)
            <p>
                Use this button to make a payment via paypal:
            </p>

            {!! Form::open(['class' => 'form-inline', 'role' => 'form']) !!}

            <div class="form-group">
                {!! Form::label('Pay for') !!}
                {!! Form::select('player', $playerOptions, null, ['class' => 'form-control', 'id' => 'player']) !!}
            </div>

            <div class="form-group">
                <button id="paypal-btn" type="submit" class="btn btn-success">Pay via Paypal</button>
            </div>

            {!! Form:: close() !!}
            @else
            <p>
                All of your league <strong>players</strong> have been payed for.  <em>This does NOT include wait listed players.  Once the wait listed players are moved to players they will appear here for payment.</em>
            </p>
            @endif
        @endif
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <h3>Liability Forms</h3>
        <p>Waivers are only required for the first CUPA event you participate in for <strong>{{ $league->year }}</strong>. If you haven't participated yet this year, please click on the link above to sign a waiver.</p>
        <p>
            If you have other trouble please contact <a href="{{ route('contact') }}">CUPA</a> or the <a href="{{ route('league_email', [$league->slug]) }}">League Director(s)</a>
        </p>
        <div class="alert alert-warning">
            Please note: we have changed waivers for youth so you may now (as a parent) sign them online.
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
<script>
$('#paypal-btn').addClass('disabled');
$('#player').on('change', function () {
    if ($(this).val() == 0) {
        $('#paypal-btn').addClass('disabled');
    } else {
        $('#paypal-btn').removeClass('disabled');
    }
});
</script>
@endsection
