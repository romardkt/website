@extends('app')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Create a Team</h2>
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @include('partials.errors')

        {!! Form::open(['class' => 'form form-vertical', 'role' => 'form', 'files' => true]) !!}
            @include('leagues.partials.team', ['submitText' => 'Create Team'])
        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('page-scripts')
@if($league->is_youth)
<script>
handleSelect2('#head_coaches', '{{ route('typeahead_users') }}', 15);
handleSelect2('#asst_coaches', '{{ route('typeahead_users') }}', 15);
</script>
@else
<script>
handleSelect2('#captains', '{{ route('typeahead_users') }}', 15);
</script>
@endif
@endsection
