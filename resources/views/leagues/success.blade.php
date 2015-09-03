@extends('layouts.master')

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
                @if($player->paid == 0 && $player->position == 'player')
                <?php $playerOptions[$player->id] = $player->user->fullname(); ?>
                @endif
                <tr>
                    <td class="col-xs-6">{{{ $player->user->fullname() }}}</td>
                    <td class="col-xs-2 text-center">{{ ($player->position == 'player') ? '<button class="btn btn-success active">Registered</button>' : '<button class="btn btn-info active">Waitlisted</button>' }}</td>
                    @if($player->user->getAge() >= 18)
                    <td class="col-xs-2 text-center">{{ ($player->user->hasWaiver($league->year)) ? '<button type="button" class="btn btn-success active">Signed</button>' : '<a href="' . route('waiver', [$league->year, $player->user->id]) . '" class="btn btn-danger">Sign Waiver</a>' }}</td>
                    @else
                    <td class="col-xs-2 text-center">{{ ($player->user->hasWaiver($league->year)) ? '<button type="button" class="btn btn-success active">Signed</button>' : '<a href="' . route('waiver_download', [$league->year, (stristr($league->name, 'yuc') === false) ? null : 'yuc']) . '" class="btn btn-danger">Sign Waiver</a>' }}</td>
                    @endif
                    <td class="col-xs-2 text-center">{{ ($player->paid) ? '<button type="button" class="btn btn-success active">Yes</button>' : '<button type="button" class="btn btn-danger active">No</button>' }}</td>
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
        <p>The cost of the league is: <strong class="text-info">{{{ ($league->registration->cost === null) ? 'Free' : '$ ' . $league->registration->cost }}}</strong></p>
        @if($league->registration->cost !== null)
        <p>
            If you are not paying online, bring your payment with you on the first night or you risk being removed from the league.
        </p>
        @if(count($playerOptions) > 1)
        <p>
            Use this button to make a payment via paypal:
        </p>

        {{ Form::open(['class' => 'form-inline', 'role' => 'form']) }}

        <div class="form-group">
            {{ Form::label('Pay for') }}
            {{ Form::select('player', $playerOptions, null, ['class' => 'form-control', 'id' => 'player']) }}
        </div>

        <div class="form-group">
            <button id="paypal-btn" type="submit" class="btn btn-success">Pay via Paypal</button>
        </div>

        {{ Form:: close() }}
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
        <p>Waivers are only required for the first CUPA event you participate in for {{{ $league->year }}}. If you haven't participated yet this year, please fill out the form below and bring it with you on the first night.</p>
        <p>If you are registering for a player <strong>18 or over</strong> please click the waiver button/link associated with the player above</p>

        <p>If you are registering for a player <strong>younger than 18</strong> please click <a href="{{ route('waiver_download', [$league->year]) }}">here</a> to download the forms for your parent to sign.  There may be more than one form to sign for certain leagues so you may want to check the information of the league <a href="{{ route('league', [$league->slug]) }}">here</a>.</p>
        <p>
            If you have trouble downloading these forms you may need Adobe Acrobat Reader.  To get it free <a href="http://get.adobe.com/reader/">click here</a>.
        </p>
        <p>
            If you have other trouble please contact <a href="{{ route('contact') }}">CUPA</a> or the <a href="{{ route('league_email', [$league->slug]) }}">League Director(s)</a>
        </p>
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
