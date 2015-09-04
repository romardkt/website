        @include('partials.errors')

        <div class="form-group">
            {!! Form::label('Board Member') !!}
            {!! Form::hidden('user_id', $initial, ['id' => 'user_id']) !!}
            <span class="help-block">Start by typing a users name</span>
        </div>

        <div class="form-group">
            @if(isset($officer) && $officer->image != '/data/users/default.png')
            <div class="current-picture">
                <div class="text-muted">Current Image</div>
                <img src="{{ asset($officer->image) }}"/>
            </div>
            {!! Form::checkbox('avatar_remove', 1, null) !!} Remove Avatar
            <br/><br/>
            @endif
            {!! Form::file('avatar', ['class' => 'form-control']) !!}
            <span class="help-block">This will replace the current image</span>
        </div>

        <div class="form-group">
            {!! Form::label('Position') !!}
            {!! Form::select('position', $officerPositions, (isset($officer->officer_position_id)) ? $officer->officer_position_id : null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Started On') !!}
            {!! Form::text('started', null, ['class' => 'form-control datepicker text-center']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Stopped On') !!}
            {!! Form::text('stopped', null, ['class' => 'form-control datepicker text-center']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Description') !!}
            {!! Form::textarea('description', null, ['class' => 'ckeditor']) !!}
        </div>
        <hr/>
        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">{{ $btnText }} Officer</button>
                <a class="btn btn-default" href="{{ route('about_board') }}">Cancel</a>
            </div>
        </div>
