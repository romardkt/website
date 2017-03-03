        <legend>Information</legend>

        <div class="form-group">
            {!! Form::label('Team Name') !!}
            {!! Form::text('display_name', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Menu Display') !!}
            {!! Form::text('menu', null, ['class' => 'form-control'])!!}
            <span class="help-block">Short name for display on the menu bar</span>
        </div>

        <div class="form-group">
            {!! Form::label('Team Division') !!}
            {!! Form::select('type', $teamTypes, null, ['class' => 'form-control select2', 'multiple']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Captain(s)') !!}
            {!! Form::hidden('captains', $initial, ['id' => 'captains']) !!}
            <span class="help-block">Start by typing a captains name</span>
        </div>

        <div class="form-group">
            {!! Form::label('Override Email') !!}
            {!! Form::text('override_email', null, ['class' => 'form-control']) !!}
            <span class="help-block">Overrides captain contact emails</span>
        </div>

        <div class="form-group">
            {!! Form::label('When team started') !!}
            {!! Form::text('begin', null, ['class' => 'form-control']) !!}
            <span class="help-block">Enter year team began</span>
        </div>

        <div class="form-group">
            {!! Form::label('When team stopped') !!}
            {!! Form::text('end', null, ['class' => 'form-control']) !!}
            <span class="help-block">Enter year team ended, leave blank if current</span>
        </div>

        <legend>Social</legend>

        <div class="form-group">
            {!! Form::label('Website link') !!}
            {!! Form::text('website', null, ['class' => 'form-control']) !!}
            <span class="help-block">Link to website (Optional)</span>
        </div>

        <div class="form-group">
            {!! Form::label('Facebook Account') !!}
            {!! Form::text('facebook', null, ['class' => 'form-control']) !!}
            <span class="help-block">Link to facebook account (Optional)</span>
        </div>

        <div class="form-group">
            {!! Form::label('Twitter Account') !!}
            {!! Form::text('twitter', null, ['class' => 'form-control']) !!}
            <span class="help-block">Link to twitter account (Optional)</span>
        </div>

        @if(isset($team) && $team->logo != '/data/users/default.png')
        <div class="current-picture">
            <div class="text-muted">Current Image</div>
            <img src="{{ asset($team->logo) }}" alt="Team logo"/>
        </div>
        <div class="form-group">
            {!! Form::checkbox('logo_remove', 1, false) !!} Remove Image
        </div>

        @endif
        <div class="form-group">
            {!! Form::label('Team Logo') !!}
            {!! Form::file('logo', null, ['class' => 'form-control']) !!}
            <span class="help-block">(Optional)</span>
        </div>

        <legend>Description</legend>

        <div class="form-group">
            {!! Form::label('Description') !!}
            {!! Form::textarea('description', null, ['class' => 'form-control ckeditor']) !!}
        </div>

        <hr/>
        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">{{ $submitText }}</button>
                @if(isset($team))
                <a class="btn btn-default" href="{{ route('teams_show', [$team->name]) }}">Cancel</a>
                @else
                <a class="btn btn-default" href="{{ route('teams') }}">Cancel</a>
                @endif
            </div>
        </div>
