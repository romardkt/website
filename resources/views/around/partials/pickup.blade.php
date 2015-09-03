        @include('layouts.partials.errors')

        <legend>Information</legend>

        <div class="form-group">
            {{ Form::label('Title') }}
            {{ Form::text('title', null, ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('Day') }}
            {{ Form::text('day', null, ['class' => 'form-control']) }}
            <span class="help-block">Enter the day(s) its played on</span>
        </div>

        <div class="form-group">
            {{ Form::label('Time') }}
            {{ Form::text('time', null, ['class' => 'form-control']) }}
            <span class="help-block">Enter the time its played at</span>
        </div>

        <div class="form-group">
            <label for="contacts">Contact</label>
            {{ Form::hidden('contacts', $initial, ['id' => 'contacts']) }}
            <span class="help-block">Start by typing a contacts name</span>
        </div>

        <div class="form-group">
            {{ Form::label('Override Email') }}
            {{ Form::text('email_override', null, ['class' => 'form-control']) }}
            <span class="help-block">If entered, it will override contacts email addresses (Optional)</span>
        </div>

        <div class="form-group">
            {{ Form::label('Visibility') }}
            <div class="checkbox">
            {{ Form::checkbox('is_visible', 1, true) }} Is Visible ?
            </div>
        </div>

        <legend>Location</legend>

        <div class="form-group">
            {{ Form::label('Location') }}
            {{ Form::select('location_id', $locations, null, ['class' => 'form-control select2']) }}
            <span class="help-block">If location does not exist you may create one below</span>
            <p>&nbsp; <button data-toggle="modal" data-target="#addLocation" type="button" class="btn btn-primary">Add a Location</button></p>
        </div>

        <legend>Description</legend>

        <div class="form-group">
            {{ Form::label('Description') }}
            {{ Form::textarea('info', null, ['class' => 'form-control ckeditor']) }}
        </div>

        <hr/>
        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">{{ $buttonText }}</button>
                <a class="btn btn-default" href="{{ route('around_pickups') }}">Cancel</a>
            </div>
        </div>
