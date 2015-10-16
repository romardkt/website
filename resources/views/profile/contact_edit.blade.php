@section('profile_content')
<div class="row">
    <div class="col-xs-12">
        @include('partials.errors')

        {!! Form::model($contact, ['class' => 'form form-vertical', 'role' => 'form']) !!}
            @include('profile.partials.contact', ['type' => 'Update'])
        {!! Form::close() !!}
    </div>
</div>
@endsection

@include('profile.header')
