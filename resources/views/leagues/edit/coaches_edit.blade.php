@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Edit {{ $member->user->fullname() }}</h2>
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @include('partials.errors')
        {!! Form::open(['class' => 'form form-vertical', 'role'=> 'form']) !!}

        <legend>Coach Information</legend>

        <div class="form-group">
            {!! Form::label('First Name') !!}
            {!! Form::text('first_name', $member->user->first_name, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Last Name') !!}
            {!! Form::text('last_name', $member->user->last_name, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Email Address') !!}
            {!! Form::email('email', $member->user->email, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Phone Number') !!}
            {!! Form::text('phone', $member->user->profile->phone, ['class' => 'form-control']) !!}
            <span class="help-block">Please use format ###-###-####</span>
        </div>

        <legend>Coaching Requirements</legend>

        <div class="form-group">
            <a target="_new" href="{{ route('youth_coaching_requirements') }}">Link to Coaching Requirements</a>
        </div>

        <div class="form-group">
            {!! Form::label('Coaching Requirements') !!}
            <div class="checkbox">
                {!! Form::checkbox('waiver', 1, $member->user->hasWaiver($league->year)) !!} Signed a waiver
            </div>

            @foreach(Config::get('cupa.coachingRequirements') as $req => $text)
            <div class="checkbox">
                @if(!in_array($req, array_keys($hiddenReqs)))
                {!! Form::checkbox($req, 1, (isset($requirements[$req])) ? $requirements[$req] : null) !!} {{ $text . '?' }}
                @endif
            </div>
            @endforeach
        </div>

        <div class="form-group">
            {!! Form::label('League Director Approval:') !!}
            <div class="checkbox">
                @foreach($hiddenReqs as $req => $text)
                    @can('edit', $league)
                    {!! Form::checkbox($req, 1, (isset($requirements[$req])) ? $requirements[$req] : null) !!} {{ $text . '?' }}
                    @else
                    {!! Form::checkbox($req, 1, (isset($requirements[$req])) ? $requirements[$req] : null, ['disabled' => 'disabled']) !!} {{ $text . '?' }}
                    @endif
                    <br/>
                @endforeach
            </div>
        </div>

        <hr/>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">Update Coach</button>
                <a class="btn btn-default" href="{{ route('league_coaches', [$league->slug]) }}">Cancel</a>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('page-scripts')
@endsection
