        <div class="form-group">
            {!! Form::label('Title') !!}
            {!! Form::text('title', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Title') !!}
            {!! Form::textarea('content', null, ['class' => 'form-control ckeditor']) !!}
        </div>

        <hr/>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">{{ $submitText }}</button>
                <a class="btn btn-default" href="{{ route('tournament', [$tournament->name, $tournament->year]) }}">Cancel</a>
            </div>
        </div>

