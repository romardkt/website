        <legend>Information</legend>

        <div class="form-group">
            {{ Form::label('Title') }}
            {{ Form::text('title', null, ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('Link') }}
            {{ Form::text('link', null, ['class' => 'form-control']) }}
            <span class="help-block">Link to lodging page (Optional)</span>
        </div>

        <div class="form-group">
            {{ Form::label('Phone') }}
            {{ Form::text('phone', null, ['class' => 'form-control']) }}
            <span class="help-block">Please use format ###-###-#### (Optional)</span>
        </div>

        <div class="form-group">
            {{ Form::label('Other informaiton') }}
            {{ Form::textarea('other', null, ['class' => 'form-control ckeditor']) }}
            <span class="help-block">Any other information? (Optional)</span>
        </div>


        <legend>Address</legend>

        <div class="form-group">
            {{ Form::label('Street') }}
            {{ Form::text('street', null, ['class' => 'form-control']) }}
            <span class="help-block">(Optional)</span>
        </div>

        <div class="form-group">
            {{ Form::label('City') }}
            {{ Form::text('city', null, ['class' => 'form-control']) }}
            <span class="help-block">(Optional)</span>
        </div>

        <div class="form-group">
            {{ Form::label('State') }}
            {{ Form::text('state', null, ['class' => 'form-control']) }}
            <span class="help-block">(Optional)</span>
        </div>

        <div class="form-group">
            {{ Form::label('Zipcode') }}
            {{ Form::text('zip', null, ['class' => 'form-control']) }}
            <span class="help-block">(Optional)</span>
        </div>

        <hr/>
        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">{{ $type }} Lodging/Information</button>
                <a class="btn btn-default" href="{{ route('tournament_location', [$tournament->name, $tournament->year]) }}">Cancel</a>
            </div>
        </div>
