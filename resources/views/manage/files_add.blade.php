@extends('app')

@section('content')

<div class="row">
    <div class="col-xs-12 col-sm-offset-2 col-sm-8">
        <legend>Add a CUPA File</legend>

        <div class="row">
            <div class="col-xs-12">
                @include('partials.errors')

                {!! Form::open(['class' => 'form form-vertical', 'role' => 'form', 'files' => true]) !!}
                <div class="form-group">
                    {!! Form::label('File') !!}
                    {!! Form::file('file', null, ['class' => 'form-control']) !!}
                </div>

                <hr/>

                <div class="form-group">
                    <div class="col-xs-12 col-sm-offset-1 text-center">
                        <button type="submit" class="btn btn-primary">Upload File</button>
                        <a class="btn btn-default" href="{{ route('manage_files') }}">Cancel</a>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
