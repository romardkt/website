        <legend>{{ $type }} Minor</legend>

        <div class="form-group">
            {{ Form::label('First Name') }}
            {{ Form::text('first_name', null, ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('Last Name') }}
            {{ Form::text('last_name', null, ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('Birthday') }}
            {{ Form::text('birthday', (isset($minor)) ? convertDate($minor->birthday, 'm/d/Y') : null, ['class' => 'form-control datepicker text-center']) }}
        </div>

        <div class="form-group">
            {{ Form::label('Gender') }}
            <div class="checkbox">
                {{ Form::radio('gender', 'Male', null, ['id' => 'gender-male']) }} &nbsp;{{ Form::label('gender-male', 'Male') }}
                &nbsp;&nbsp;&nbsp;&nbsp;
                {{ Form::radio('gender', 'Female', null, ['id' => 'gender-female']) }} &nbsp;{{ Form::label('gender-female', 'Female') }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('Nickname') }}
            {{ Form::text('nickname', (isset($minor)) ? $minor->profile->nickname : null, ['class' => 'form-control']) }}
            <span class="help-block">(Optional) Leave blank for none</span>
        </div>

        <div class="form-group">
            {{ Form::label('Height') }}
            {{ Form::number('height', (isset($minor)) ? $minor->profile->height : null, ['class' => 'form-control']) }}
            <span class="help-block">Height in inches</span>
        </div>

        <div class="form-group">
            {{ Form::label('Level') }}
            {{ Form::select('level', array_combine(Config::get('cupa.levels'), Config::get('cupa.levels')), (isset($minor)) ? $minor->profile->level : null, ['class' => 'form-control']) }}
            <span class="help-block">Select the highest level you have played</span>
        </div>

        <div class="form-group">
            {{ Form::label('Experience') }}
            {{ Form::text('experience', (isset($minor)) ? $minor->profile->experience : null, ['class' => 'form-control']) }}
            <span class="help-block">Enter the year you started playing</span>
        </div>

        <div class="form-group">
            <div class="checkbox">
                {{ Form::checkbox('consent', 1, false) }} I consent to allowing the collection of my minor's information
                <span class="help-block"><a target="_blank" href="http://www.ecfr.gov/cgi-bin/text-idx?SID=4939e77c77a1a1a08c1cbf905fc4b409&node=16%3A1.0.1.3.36&rgn=div5">Children's Online Privacy Protection Regulations</a>
            </div>
            <div class="alert alert-warning">
                This minor's data will <strong>NOT</strong> be used except for the leagues that this minor will be registering for.  It will only be used to help place/organize the league and nothing else.
            </div>
        </div>

        <hr/>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">{{ $type }} Minor</button>
                @if($type == 'Update')
                <a class="btn btn-danger" onclick="return confirm('Are you sure?');" href="{{ route('profile_minor_remove', [$minor->id]) }}">Remove!</a>
                @endif
                <a class="btn btn-default" href="{{ route('profile_minors') }}">Cancel</a>
            </div>
        </div>
