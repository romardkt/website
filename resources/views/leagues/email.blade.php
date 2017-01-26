@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">{{ $league->displayName() }} Email</h2>
    </div>
</div>
@include('leagues.header')
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @include('partials.errors')

        {!! Form::open(['class' => 'form form-vertical', 'role'=> 'form']) !!}
        <legend>Contact League Personnel</legend>
        <p>
            You can use this form to contact for information or questions about this league. If you only have the selection of contacting the directors then you must be a member of the league and be assigned a team to email your team and your captain.
        </p>

        <hr/>

        <div class="form-group">
            <label for="to" >To</label>
            <div class="text-center">
                <div class="btn-group">
                    @foreach($tos as $display => $value)
                    @if(Request::old('to') !== null && in_array($display, explode(',', Request::old('to'))))
                    <a class="btn btn-primary to-select active" data-value="{{ $display }}">{{ ucwords(str_replace('-', ' ', $display)) }}</a>
                    @else
                    <a class="btn btn-default to-select" data-value="{{ $display }}">{{ ucwords(str_replace('-', ' ', $display)) }}</a>
                    @endif
                    @endforeach
                </div>
            </div>
            {!! Form::hidden('to', null, ['id' => 'to']) !!}
            <span class="help-block">Click to select who you want to send the message to</span>
        </div>

        <div class="form-group">
            {!! Form::label('Your Email') !!}
            {!! Form::text('from', (Auth::check()) ? Auth::user()->email : null, ['class' => 'form-control']) !!}
            <span class="help-block">Make sure this is correct to receive a reply</span>
        </div>

        <div class="form-group">
            {!! Form::label('Your Name') !!}
            {!! Form::text('name', (Auth::check()) ? Auth::user()->fullname() : null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Subject') !!}
            {!! Form::text('subject', (Request::old('subject')) ? Request::old('subject') : '[' . $league->displayName() . '] Information', ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Message Content') !!}
            {!! Form::textarea('body', null, ['class' => 'form-control ckeditor']) !!}
        </div>

        @if(Auth::guest())
        <div class="form-group">
            {!! Form::label('Verify you\'re human') !!}
            {!! Form::captcha() !!}
        </div>
        @endif

        <hr/>
        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button type="submit" id="email-submit-btn" class="btn btn-primary">Send Email Message</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('page-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.6.2/ckeditor.js"></script>
<script>
    var toSelect = [];
    $('.to-select').on('click touchstart', function (e) {
        e.preventDefault();

        if ($(this).hasClass('active')) {
            $(this).removeClass('active')
            $(this).removeClass('btn-primary');
            $(this).addClass('btn-default');
        } else {
            $(this).addClass('active')
            $(this).removeClass('btn-default');
            $(this).addClass('btn-primary');
        }

        toSelect = [];
        $('.to-select.active').each(function (i, item) {
            toSelect.push($(item).data('value'));
        });

        $('#to').val(toSelect);
    });

</script>
@endsection
