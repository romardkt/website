@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Edit {{{ $league->displayName() }}} Description</h2>
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @include('layouts.partials.errors')

        {{ Form::open(['class' => 'form form-vertical', 'role'=> 'form']) }}

        <div class="form-group">
            {{ Form::label('Description') }}
            {{ Form::textarea('description', $league->description, ['class' => 'form-control ckeditor']) }}
        </div>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">Update Description</button>
                <a class="btn btn-default" href="{{ route('league', [$league->slug]) }}">Cancel</a>
            </div>
        </div>

        {{ Form::close() }}
    </div>
</div>
@endsection

@section('page-scripts')
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
@endsection
