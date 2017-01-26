@extends('tournament')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Edit Tournament Schedule</h2>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @include('partials.errors')

        {!! Form::model($tournament, ['class' => 'form form-vertical', 'role' => 'form']) !!}

        <div class="form-group">
            {!! Form::label('Schedule Information') !!}
            {!! Form::textarea('schedule', null, ['class' => 'form-control ckeditor']) !!}
        </div>

        <hr/>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">Update Schedule</button>
                <a class="btn btn-default" href="{{ route('tournament_schedule', [$tournament->name, $tournament->year]) }}">Cancel</a>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('page-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.6.2/ckeditor.js"></script>
@endsection
