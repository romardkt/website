        @include('partials.errors')

        <div class="form-group">
            {!! Form::label('Location') !!}
            {!! Form::select('location_id', $locations, null, ['class' => 'form-control select2']) !!}
            <span class="help-block">If location does not exist you may create one below</span>
            <p>&nbsp; <button data-toggle="modal" data-target="#addLocation" type="button" class="btn btn-primary">Add a Location</button></p>
        </div>
        <div class="form-group">
            {!! Form::label('Start Date') !!}
            {!! Form::text('start_date', (isset($minute->start)) ? date('m/d/Y', strtotime($minute->start)) : null, ['class' => 'datepicker text-center form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('Start Time') !!}
            {!! Form::text('start_time', (isset($minute->start)) ? date('h:i A', strtotime($minute->start)) : null, ['class' => 'clockpicker text-center form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('End Date') !!}
            {!! Form::text('end_date', (isset($minute->end)) ? date('m/d/Y', strtotime($minute->end)) : null, ['class' => 'datepicker text-center form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('End Time') !!}
            {!! Form::text('end_time', (isset($minute->end)) ? date('h:i A', strtotime($minute->end)) : null, ['class' => 'clockpicker text-center form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('PDF File') !!}
            {!! Form::file('pdf', ['class' => 'form-control']) !!}
            <span class="help-block">(Optional)</span>
        </div>
        <hr/>
        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">{{ $buttonText }}s</button>
                <a class="btn btn-default" href="{{ route('about_minutes') }}">Cancel</a>
            </div>
        </div>
