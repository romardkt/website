        <legend>{{ $type }} Contact</legend>

        <div class="form-group">
            {!! Form::label('Name') !!}
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Phone') !!}
            {!! Form::text('phone', null, ['class' => 'form-control']) !!}
            <span class="help-block">Format: ###-###-####</span>
        </div>

        <hr/>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">{{ $type }} Contact</button>
                @if($type == 'Update')
                <a class="btn btn-danger" onclick="return confirm('Are you sure?');" href="{{ route('profile_contact_remove', [$contact->id]) }}">Remove!</a>
                @endif
                <a class="btn btn-default" href="{{ route('profile_contacts') }}">Cancel</a>
            </div>
        </div>
