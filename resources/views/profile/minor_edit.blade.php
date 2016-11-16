@section('page-scripts')
<script>
$('.datepicker').pickadate({
    format: 'mm/dd/yyyy',
    editable: false,
    selectYears: 70,
    selectMonths: true,
    min: -25567,
    max: -1,
});
</script>
@endsection

@section('profile_content')
<div class="row">
    <div class="col-xs-12">
        @include('partials.errors')
        @if($minor->getAge() >= 18)
        <div class="alert alert-info">
            <a href="{{route('profile_minors_convert', $minor->id)}}">
                This minor is 18 or older and can be moved to their own account if desired.  Just click this message to start.
            </a>
        </div>
        @endif

        {!! Form::model($minor, ['class' => 'form form-vertical', 'role' => 'form']) !!}
            @include('profile.partials.minor', ['type' => 'Update'])
        {!! Form::close() !!}
    </div>
</div>
@endsection

@include('profile.header')
