@section('profile_content')
<div class="row">
    <div class="col-xs-12">
        @include('layouts.partials.errors')

        {{ Form::open(['class' => 'form form-vertical', 'role' => 'form']) }}
            @include('profile.partials.contact', ['type' => 'Add a'])
        {{ Form::close() }}
    </div>
</div>
@endsection

@include('profile.header')
