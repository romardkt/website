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
      <legend>Mass Addition/Removal of Volunteers</legend>

      @if($errors->any())
      <ul>
        @foreach($errors->keys() as $email)
          <li>{{$email}} - {!!$errors->first($email)!!}</li>
        @endforeach
      </ul>
      @endif

      {!! Form::open(['class' => 'form form-vertical', 'role' => 'form']) !!}
          {!! Form::textarea('emails', null, ['class' => 'form-control']) !!}
          <br/>
          <div class="form-group text-right">
              <button type="submit" class="btn btn-primary" name="action" value="addition">Add Volunteers</button>
              <button type="submit" class="btn btn-danger" name="action" value="remove">Remove Volunteers</button>
          </div>
      {!! Form::close() !!}
    </div>
</div>
@endsection

@section('page-scripts')
@endsection
