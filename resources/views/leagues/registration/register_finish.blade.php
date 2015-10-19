<div class="row">
    <div class="col-xs-12 col-sm-offset-2 col-sm-8">
        @include('partials.errors')

        {!! Form::open(['class' => 'form form-vertical', 'role' => 'form']) !!}

        <legend>Finish Registration</legend>

        <div class="alert alert-warning">
            <h4>Please check your information</h4>
            <p>This is your contact information for the league, please make sure they are correct.  If not you may go back to update the information.</p>
        </div>

        <div class="form-group">
            {!! Form::label('Email Address') !!}
            {!! Form::email('email', $session->info['email'], ['class' => 'form-control', 'disabled']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('First Name') !!}
            {!! Form::text('first_name', $session->info['first_name'], ['class' => 'form-control', 'disabled']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Last Name') !!}
            {!! Form::text('last_name', $session->info['last_name'], ['class' => 'form-control', 'disabled']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Email Address') !!}
            {!! Form::text('phone', $session->info['phone'], ['class' => 'form-control', 'disabled']) !!}
        </div>

        <hr>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <a class="btn btn-default" href="{{ route('league_register', [$league->slug, 'league']) }}"><i class="fa fa-fw fa-lg fa-arrow-left"></i> Back</a>
                <button type="submit" class="btn btn-success"><i class="fa fa-fw fa-lg fa-check"></i> Complete {{{ ucfirst($type) }}}</button>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
</div>
