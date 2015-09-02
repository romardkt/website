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
        <legend>CUPA Users</legend>

        {!! Form::text('user', null, ['class' => 'form-control', 'id' => 'user-select']) !!}

        <div id="data"></div>
    </div>
</div>
@endsection

@section('page-scripts')
<script>
$('#data').hide();
handleSelect2('#user-select', '{{ route('typeahead_users') }}', 1);

$('#user-select').on('click', function (e) {
    var userId = $(this).val();
    if (userId == 0) {
        $('#data').hide();
    } else {
        $.ajax({
            url: '{{ route('manage_users_detail') }}',
            type: 'post',
            data: { _token: '{{ csrf_token() }}', user_id: $(this).val() },
            success: function (resp) {
                $('#data').html(resp);
                $('#data').fadeIn();
            }
        });
    }
});
</script>
@endsection
