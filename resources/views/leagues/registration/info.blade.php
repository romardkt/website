<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @include('partials.errors')

        {!! Form::open(['class' => 'form form-vertical', 'role' => 'form']) !!}

        <legend>Enter Player information</legend>

        <div class="form-group">
            @if ($session->registrant->parent === null)
            {!! Form::email('email', $session->registrant->email, ['class' => 'form-control']) !!}
            @else
            {!! Form::email('email', $session->registrant->parentObj->email, ['class' => 'form-control']) !!}
            <span class="help-block">This is the parents email address and cannot be changed here</span>
            @endif
        </div>

        <div class="form-group">
            {!! Form::label('First Name') !!}
            {!! Form::text('first_name', $session->registrant->first_name, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Last Name') !!}
            {!! Form::text('last_name', $session->registrant->last_name, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Birthday') !!}
            {!! Form::input('text', 'birthday', convertDate($session->registrant->birthday, 'm/d/Y'), ['class' => 'form-control datepicker text-center']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Gender') !!}
            <div class="checkbox">
                {!! Form::radio('gender', 'Male', $session->registrant->gender == 'Male', ['id' => 'gender-male']) !!} &nbsp;
                {!! Form::label('gender-male', 'Male') !!}
                &nbsp;&nbsp;&nbsp;&nbsp;
                {!! Form::radio('gender', 'Female', $session->registrant->gender == 'Female', ['id' => 'gender-female']) !!} &nbsp;
                {!! Form::label('gender-female', 'Female') !!}
            </div>
        </div>

        <legend>Extra Player information</legend>

        <div class="form-group">
            {!! Form::label('Phone #') !!}
            @if ($session->registrant->parent === null)
            {!! Form::text('phone', $session->registrant->profile->phone, ['class' => 'form-control']) !!}
            @else
            {!! Form::text('phone', $session->registrant->parentObj->profile->phone, ['class' => 'form-control']) !!}
            @endif
            <span class="help-block">Format: ###-###-####</span>
        </div>

        <div class="form-group">
            {!! Form::label('Nickname') !!}
            {!! Form::text('nickname', $session->registrant->profile->nickname, ['class' => 'form-control']) !!}
            <span class="help-block">(Optional)</span>
        </div>

        <div class="form-group">
            {!! Form::label('Height') !!}
            {!! Form::text('height', $session->registrant->profile->height, ['class' => 'form-control']) !!}
            <span class="help-block">Enter height in inches</span>
        </div>

        <div class="form-group">
            {!! Form::label('Select Highest Level') !!}
            {!! Form::select('level', array_combine(Config::get('cupa.levels'), Config::get('cupa.levels')), $session->registrant->profile->level, ['class' => 'form-control']) !!}
            <span class="help-block">Select the highest level played</span>
        </div>

        <div class="form-group">
            {!! Form::label('What year did you start playing?') !!}
            {!! Form::text('experience', $session->registrant->profile->experience, ['class' => 'form-control']) !!}
            <span class="help-block">Enter the year you started playing ultimate</span>
        </div>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <a class="btn btn-default" href="{{ route('league_register', [$league->slug, 'who']) }}"><i class="fa fa-fw fa-lg fa-arrow-left"></i> Back</a>
                <button type="submit" class="btn btn-primary">Next <i class="fa fa-fw fa-lg fa-arrow-right"></i></button>
            </div>
        </div>
    </div>
</div>

@section('page-scripts')
@if($league->is_youth)
<script>
    $('.datepicker').pickadate({
        format: 'mm/dd/yyyy',
        editable: false,
        selectYears: 70,
        selectMonths: true,
        min: -25567,
        max: -1
    });
</script>
@else
<script>
    $('.datepicker').pickadate({
        format: 'mm/dd/yyyy',
        editable: false,
        selectYears: 70,
        selectMonths: true,
        min: -25567,
        max: -6575
    });
</script>
@endif
@endsection
