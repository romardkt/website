        <legend>Information</legend>

        <div class="form-group">
            {{ Form::label('Category') }}
            {{ Form::select('category', $volunteerCategories, (isset($event)) ? $event->volunteer_event_category_id : null, ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('Title') }}
            {{ Form::text('title', null, ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('Contact(s)') }}
            {{ Form::hidden('contacts', $initial, ['id' => 'contacts']) }}
            <span class="help-block">Start by typing a contacts name</span>
        </div>

        <div class="form-group">
            {{ Form::label('Email Override') }}
            {{ Form::email('email_override', null, ['class' => 'form-control']) }}
            <span class="help-block">If this is entered use this as the contact email (optional)</span>
        </div>

        <div class="form-group">
            {{ Form::label('Start Date') }}
            {{ Form::text('start_date', (isset($event)) ? convertDate($event->start, 'm/d/Y') : null, ['class' => 'form-control datepicker text-center']) }}
        </div>

        <div class="form-group">
            {{ Form::label('Start Time') }}
            {{ Form::text('start_time', (isset($event)) ? convertDate($event->start, 'h:i A') : null, ['class' => 'form-control clockpicker text-center']) }}
        </div>

        <div class="form-group">
            {{ Form::label('End Date') }}
            {{ Form::text('end_date', (isset($event)) ? convertDate($event->end, 'm/d/Y') : null, ['class' => 'form-control datepicker text-center']) }}
        </div>

        <div class="form-group">
            {{ Form::label('End Time') }}
            {{ Form::text('end_time', (isset($event)) ? convertDate($event->end, 'h:i A') : null, ['class' => 'form-control clockpicker text-center']) }}
        </div>

        <div class="form-group">
            {{ Form::label('Number of Volunteers Needed') }}
            {{ Form::number('num_volunteers', null, ['class' => 'form-control']) }}
        </div>


        <legend>Location</legend>

        <div class="form-group">
            {{ Form::label('Location') }}
            {{ Form::select('location_id', $locations, null, ['class' => 'form-control select2']); }}
            <span class="help-block">If location does not exist you may create one below</span>
            <p>&nbsp; <button data-toggle="modal" data-target="#addLocation" type="button" class="btn btn-primary">Add a Location</button></p>
        </div>

        <legend>Description</legend>

        <div class="form-group">
            {{ Form::label('Description') }}
            {{ Form::textarea('information', null, ['class' => 'form-control ckeditor']) }}
        </div>

        <hr/>
        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">{{ $type }} Opportunity</button>
                <a class="btn btn-default" href="{{ route('volunteer_show') }}">Cancel</a>
            </div>
        </div>
