@section('profile_content')
<legend>Coaching Requirements</legend>

<div class="coaching-requirements">
    {!! Form::open(['class' => 'form form-vertical', 'role'=> 'form']) !!}

    <div class="form-group">
        <a target="_new" href="{{ route('youth_coaching_requirements') }}">Link to Coaching Requirements</a>
    </div>

    <div class="form-group">
        {!! Form::label('Coaching Requirements') !!}
        @if($user->hasWaiver(date('Y')))
        <div class="checkbox">
            {!! Form::checkbox('waiver', 1, $user->hasWaiver(date('Y')), ['disabled' => 'disabled']) !!} Signed a waiver
        </div>
        @else
        <div class="checkbox">
            <a href="{{ route('waiver', date('Y')) }}">Sign a waiver</a>
        </div>
        @endif

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
                {!! Form::checkbox($req, 1, (isset($requirements[$req])) ? $requirements[$req] : null, ['disabled' => 'disabled']) !!} {{ $text . '?' }}
                <br/>
            @endforeach
        </div>
    </div>

    <hr/>

    <div class="form-group">
        <div class="col-xs-12 text-center">
            <button type="submit" class="btn btn-primary">Update Requirements</button>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@endsection

@include('profile.header')
