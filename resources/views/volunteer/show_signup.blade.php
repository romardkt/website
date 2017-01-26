@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">{{ $event->title }} Sign Up</h2>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-xs-12 col-md-10 col-md-offset-1">
        @include('partials.errors')

        <?php $select = false; ?>
        {!! Form::open(['class' => 'form form-vertical', 'role' => 'form']) !!}
        @foreach(json_decode($event->category->questions) as $question)
        <div class="form-group">
            {!! Form::label($question->title) !!}
        @if($question->type == 'checkboxes')
        <?php $options = []; ?>
        @foreach($question->answers as $answer)
        <?php $options[$answer] = $answer; ?>
        @endforeach
        <?php $select = true; ?>
        {!! Form::select($question->name . '[]', $options, null, ['class' => 'form-control select2', 'multiple']) !!}
        @elseif($question->type == 'radio')
        @foreach($question->answers as $key => $answer)
        <div class="checkbox">
            {!! Form::radio($question->name, $key, false) !!} {{ $answer }}
        </div>
        @endforeach
        @elseif ($question->type == 'textarea')
        {!! Form::textarea($question->name, null, ['class' => 'form-control', 'rows' => 10]) !!}
        @elseif ($question->type == 'description')
        {{ $question->answers }}
        @else
        {!! Form::text($question->name, null, ['class' => 'form-control']) !!}
        @endif
        <span class="help-block">{{ (isset($question->help)) ? $question->help : '' }}</span>
        </div>
        @endforeach

        <div class="form-group">
            {!! Form::label('Comments/Questions') !!}
            {!! Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 10]) !!}
        </div>

        <hr/>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-primary">Sign up for Opportunity</button>
                <a class="btn btn-default" href="{{ route('volunteer_show') }}">Cancel</a>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
</div>

@endsection

@section('page-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.6.2/ckeditor.js"></script>
@if($select)
<script>
$('.select2').select2({placeholder: 'Click to select an answer.'});
</script>
@endif
@endsection
