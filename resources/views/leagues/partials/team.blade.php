        <div class="form-group">
            {!! Form::label('Team Name') !!}
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>

        @if($league->is_youth)
        <div class="form-group">
            {!! Form::label('Head Coach(es)') !!}
            {!! Form::hidden('head_coaches', $initialHc, ['id' => 'head_coaches']) !!}
             <span class="help-block">Start by typing a head coaches name</span>
        </div>
        <div class="form-group">
            {!! Form::label('Assistant Coach(es)') !!}
            {!! Form::hidden('asst_coaches', $initialAc, ['id' => 'asst_coaches']) !!}
             <span class="help-block">Start by typing an assistant coaches name</span>
        </div>
        @else
        <div class="form-group">
            {!! Form::label('Captain(s)') !!}
            {!! Form::hidden('captains', $initialC, ['id' => 'captains']) !!}
            <span class="help-block">Start by typing a captains name</span>
        </div>
        @endif

        <div class="form-group">
            {!! Form::label('Team Color') !!}
            {!! Form::text('color', null, ['class' => 'form-control']) !!}
            <span class="help-block">Enter the color in english</span>
        </div>

        <div class="form-group">
            {!! Form::label('Visual Color') !!}
            {!! Form::input('color', 'color_code', null, ['class' => 'form-control']) !!}
            <span class="help-block">Select the color for the team</span>
        </div>


        @if(isset($team) && $team->logo != '/data/users/default.png')
        <div class="current-picture">
            <div class="text-muted">Current Logo</div>
            <img src="{{ asset($team->logo) }}" alt="Team logo"/>
        </div>
        {!! Form::checkbox('logo_remove', 1, null) !!} Remove Logo
        @endif

        <div class="form-group">
            {!! Form::label('Team Logo') !!}
            {!! Form::file('logo', null, ['class' => 'form-control']) !!}
            <span class="help-block">This will replace the current image</span>
        </div>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">{{ $submitText }}</button>
                <a class="btn btn-default" href="{{ route('league_teams', [$league->slug]) }}">Cancel</a>
            </div>
        </div>
