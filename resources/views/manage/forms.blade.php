@extends('app')

@section('content')

<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <h2 class="page">CUPA Management</h2>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-2 text-center">
        @include('manage.menu')
    </div>
    <div class="col-xs-12 col-sm-8">
        <legend>CUPA Forms</legend>
        <div class="row text-right">
            <div class="col-xs-12">
                <a class="btn btn-default" href="{{ route('manage_forms_add') }}"><i class="fa fa-lg fa-fw fa-plus"></i> Add Form</a>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-xs-12">
                <div class="list-group">
                    @foreach($forms as $form)
                    <div class="list-group-item">
                        <div class="pull-right">
                            <a class="btn btn-default" href="{{ route('form_view', [$form->slug]) }}"><i class="fa fa-lg fa-fw fa-download"></i><span class="hidden-xs hidden-sm"> Download</span></a>
                            <a class="btn btn-default" href="{{ route('manage_forms_edit', [$form->slug]) }}"><i class="fa fa-lg fa-fw fa-edit"></i><span class="hidden-xs hidden-sm"> Update</span></a>
                            <a class="btn btn-danger" href="{{ route('manage_forms_remove', [$form->slug]) }}" onclick="return confirm('Are you sure you want to remove this form?');"><i class="fa fa-lg fa-fw fa-trash-o"></i><span class="hidden-xs hidden-sm">  Delete</span></a>
                        </div>
                        <h4 class="list-group-item-heading">{{ $form->year . ' ' . $form->name }}</h4>
                        <p class="list-group-item-text">
                            <span class="text-muted">{{ $form->slug }}</span>
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
