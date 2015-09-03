@extends('layouts.tournament')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Add a Contact</h2>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @include('layouts.partials.errors')

        {{ Form::open(['class' => 'form form-vertical', 'role' => 'form']) }}

        <div class="form-group">
            {{ Form::label('User') }}
            {{ Form::hidden('user_id', $initial, ['id' => 'user_id']) }}
            <span class="help-block">Start by typing a users name</span>
        </div>

        <div class="form-group">
            {{ Form::label('Position') }}
            {{ Form::text('position', null, ['class' => 'form-control']) }}
        </div>

        <hr/>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">Add Contact</button>
                <a class="btn btn-default" href="{{ route('tournament_contact', [$tournament->name, $tournament->year]) }}">Cancel</a>
            </div>
        </div>

        {{ Form::close() }}
    </div>
</div>
@endsection

@section('page-scripts')
<script>
handleSelect2('#user_id', '{{ route('typeahead_users') }}', 1);
</script>
@endsection
