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
        @include('layouts.partials.errors')

        {{ Form::model($minor, ['class' => 'form form-vertical', 'role' => 'form']) }}
            @include('profile.partials.minor', ['type' => 'Update'])
        {{ Form::close() }}
    </div>
</div>
@endsection

@include('profile.header')
