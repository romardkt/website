@extends('tournament')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Edit Tournament Bid</h2>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-xs-12 col-sm-offset-2 col-sm-8">
        @include('partials.errors')

        {!! Form::model($tournament, ['class' => 'form form-vertical', 'role' => 'form']) !!}

        <legend>Bid Information</legend>

        <div class="form-group">
            {!! Form::label('Tournament Fee') !!}
            {!! Form::number('cost', null, ['class' => 'form-control']) !!}
            <span class="help-block">Enter the tourney fee</span>
        </div>

        <div class="form-group">
            {!! Form::label('Bid Due Date') !!}
            {!! Form::text('bid_due_date', convertDate($tournament->bid_due,'m/d/Y'), ['class' => 'form-control datepicker text-center']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Bid Due Time') !!}
            {!! Form::text('bid_due_time', convertDate($tournament->bid_due,'h:i A'), ['class' => 'form-control clockpicker text-center']) !!}
        </div>

        <legend>Paypal Settings</legend>

        <div class="form-group">
            {!! Form::label('Paypal Link') !!}
            <div class="checkbox">
                {!! Form::radio('paypal_type', 0, $tournament->use_paypal == 1 && empty($tournament->paypal)) !!} CUPA Paypal
            </div>
            <div class="checkbox">
                {!! Form::radio('paypal_type', 1, $tournament->use_paypal == 1 && !empty($tournament->paypal)) !!} Custom Paypal
            </div>
            <div class="checkbox">
                {!! Form::radio('paypal_type', 2, $tournament->use_paypal == 0) !!} No Paypal
            </div>
            <span class="help-block">Select the type of payments</span>
        </div>

        <div class="form-group">
            {!! Form::label('Paypal Button/Text') !!}
            {!! Form::textarea('paypal', null, ['class' => 'form-control', 'rows' => 4]) !!}
            <span class="help-block">Enter paypal message/button/link (Optional)</span>
        </div>

        <div class="form-group">
            {!! Form::label('Mail Address') !!}
            {!! Form::textarea('mail', null, ['class' => 'form-control', 'rows' => 4]) !!}
            <span class="help-block">Enter mailing address for payments (Optional)</span>
        </div>

        <hr/>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">Update Bid Settings</button>
                <a class="btn btn-default" href="{{ route('tournament_bid', [$tournament->name, $tournament->year]) }}">Cancel</a>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('page-scripts')
<script>
$('.datepicker').pickadate({
    format: 'mm/dd/yyyy',
    editable: false,
    selectYears: true,
    selectMonths: true
});
$('.clockpicker').clockpicker({
    donetext: 'Done',
    twelvehour: true,
    align: 'right',
});
</script>
@endsection
