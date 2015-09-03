        <legend>Team Information</legend>

        <div class="form-group">
            {{ Form::label('Division') }}
            {{ Form::select('division', $divisions, null, ['class' => 'form-control select2']) }}
        </div>

        <div class="form-group">
            {{ Form::label('Name') }}
            {{ Form::text('name', null, ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('City') }}
            {{ Form::text('city', null, ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('State') }}
            {{ Form::text('state', null, ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            <div class="checkbox">
                {{ Form::checkbox('accepted', 1, false) }} Is team accepted?
            </div>
        </div>

        <div class="form-group">
            <div class="checkbox">
                {{ Form::checkbox('paid', 1, false) }} Has team paid?
            </div>
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

        <hr/>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">{{ $type }} Team</button>
                @if($type == 'Update')
                <a class="btn btn-default" href="{{ route('tournament_teams', [$tournament->name, $tournament->year, $team->division]) }}">Cancel</a>
                @else
                <a class="btn btn-default" href="{{ route('tournament_teams', [$tournament->name, $tournament->year]) }}">Cancel</a>
                @endif
            </div>
        </div>
