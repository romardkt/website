@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">{{ $league->displayName() }} Coaches</h2>
    </div>
</div>
@include('leagues.header')
<div class="row">
    <div class="col-sm-offset-2 col-sm-8">
        @include('layouts.partials.errors')

        {{ Form::open(['class' => 'form-vertical', 'role'=> 'form']) }}

        <div class="form-group">
            {{ Form::label('From Email Address') }}
            {{ Form::email('from', $isAuthorized['userData']->email, ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('From Name') }}
            {{ Form::text('name', $isAuthorized['userData']->fullname(), ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('Subject') }}
            {{ Form::text('subject', '[CUPA] ' . $league->year . ' Coaching Requirements', ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('Message') }}
            {{ Form::textarea('message', null, ['class' => 'form-control']) }}
            <span class="help-block">This will be added to the message of what requirements the coach is missing</span>
        </div>

        <hr/>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" id="email-submit-btn" class="btn btn-primary">Send Email Message</button>
                <a class="btn btn-default" href="{{ route('league_coaches', [$league->slug]) }}">Cancel</a>
            </div>
        </div>

        {{ Form::close() }}
    </div>
</div>
@endsection

@section('page-scripts')
@endsection
