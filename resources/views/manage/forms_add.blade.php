@extends('app')

@section('content')

<div class="row">
    <div class="col-xs-12 col-sm-offset-2 col-sm-8">
        <legend>Add a CUPA Form</legend>

        <div class="row">
            <div class="col-xs-12">
                @include('partials.errors')

                {!! Form::open(['class' => 'form form-vertical', 'role' => 'form', 'files' => true]) !!}
                    @include('manage.partials.form', ['submitText' => 'Add Form'])
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
