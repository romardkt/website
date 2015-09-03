        <div class="form-group">
            {{ Form::label('Name') }}
            {{ Form::text('name', null, ['class' => 'form-control']) }}
            <span class="help-block">The name you wan in the url</span>
        </div>

        <div class="form-group">
            {{ Form::label('Title') }}
            {{ Form::text('display', null, ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            {{ Form::label('') }}
            {{ Form::textarea('content', null, ['class' => 'form-control ckeditor', 'rows' => 15]) }}
        </div>

        <hr/>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">{{ $type }} Clinic</button>
                @if(isset($clinic))
                <a class="btn btn-default" href="{{ route('youth_clinic', [$clinic->name]) }}">Cancel</a>
                @else
                <a class="btn btn-default" href="{{ route('youth_clinics') }}">Cancel</a>
                @endif
            </div>
        </div>
