@extends('tournament')

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <h1 class="title">{{{ $tournament->display_name }}} Bid Information</h1>
    </div>
</div>
@if($tournament->bid_due >= (new DateTime())->format('Y-m-d H:i:s'))
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <p>
            To submit a bid you must fill out this form with your team's information. Please fill in all
            information so we can enter you into the correct spot for the tournament. If you fail to enter in the
            correct information you risk being bumped by a team that fills out the information correctly.
        </p>
        <p>
            The cost for this tournament is <strong class="text-info">${{{ $tournament->cost }}}</strong>, which you may pay
            upon successful bid submission with Paypal.  If you have already submitted a bid and would just like
            to pay the tournament fee you may
        </p>
        <p>
            <a class="btn btn-primary" href="{{ route('tournament_payment', [$tournament->name, $tournament->year]) }}">Make a Payment for your team</a>
        </p>
        <hr/>
        <p>
            <h4 class="text-warning text-center">Bid is due {{{ (new DateTime($tournament->bid_due))->format('M j Y @ h:i A') }}}</h4>
        </p>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @include('layouts.partials.errors')

        {{ Form::open(['class' => 'form form-vertical', 'role' => 'form']) }}

        <legend>Team Information</legend>

        <div class="form-group">
            {{ Form::label('Division') }}
            {{ Form::select('division', $divisions, null, ['class' => 'form-control select2']) }}
        </div>

        <div class="form-group">
            {{ Form::label('Team Name') }}
            {{ Form::text('name', null, ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('City') }}
            {{ Form::text('city', null, ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('State') }}
            {{ Form::text('state', null, ['class' => 'form-control']) }}
            <span class="help-block">Please use state abbreviation (i.e. OH, IL, etc.)</span>
        </div>

        <legend>Team Contact</legend>

        <div class="form-group">
            {{ Form::label('Contact Name') }}
            {{ Form::text('contact_name', null, ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('Contact Phone') }}
            {{ Form::text('contact_phone', null, ['class' => 'form-control']) }}
            <span class="help-block">Please use this format: ###-###-####</span>
        </div>

        <div class="form-group">
            {{ Form::label('Contact Email Address') }}
            {{ Form::email('contact_email', null, ['class' => 'form-control']) }}
        </div>

        <legend>Other</legend>

        <div class="form-group">
            {{ Form::label('Any Comments/Questions') }}
            {{ Form::textarea('comments', null, ['class' => 'form-control ckeditor']) }}
        </div>

        <div class="form-group">
            {{ Form::label('Verify you\'re human') }}
            {{ Form::captcha() }}
        </div>

        <hr/>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">Submit Bid</button>
                <a class="btn btn-default" href="{{ route('tournament_bid', [$tournament->name, $tournament->year]) }}">Cancel</a>
            </div>
        </div>

        {{ Form::close() }}
    </div>
</div>
@else
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <div class="alert alert-warning">
            <h4>Bid submission ended</h4>
            <p>The date to submit a bid has passed so you may not submit a bid. If you want to contact the tournament
            director you may do so <a href="{{ route('tournament_contact', [$tournament->name, $tournament->year]) }}">here</a></p>
        </div>
        <p>
            If you have already submitted a bid and would like to pay for the tournament you can find the payment information below:
        </p>
        <hr/>
        <p>
            <a class="btn btn-primary" href="{{ route('tournament_payment', [$tournament->name, $tournament->year]) }}">Make a Payment for your team</a>
        </p>
    </div>
</div>
@endif
@endsection

@section('page-scripts')
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<script>
$('.select2').select2({
    placeholder: 'Select a division'
});
</script>
@endsection
