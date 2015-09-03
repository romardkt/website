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
        {!! Form::open(['class' => 'form form-vertical', 'role' => 'form']) !!}

        <legend>Move League Player</legend>

        <div class="form-group">
            {!! Form::label('From League') !!}
            {!! Form::select('source', $leagues, null, ['class' => 'form-control', 'id' => 'source']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('Player') !!}
            {!! Form::text('source_player', null, ['class' => 'form-control', 'id' => 'source_player']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('To League') !!}
            {!! Form::select('to', $leagues, null, ['class' => 'form-control', 'id' => 'to']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('To Team') !!}
            {!! Form::select('to_team', [], null, ['class' => 'form-control', 'id' => 'to_team']) !!}
        </div>

        <hr/>

        <div class="form-group">
            <div class="col-xs-12 text-center">
                <button id="submit" type="submit" class="btn btn-primary">Move Player</button>
                <button id="reset" class="btn btn-default" type="button">Cancel</a>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('page-scripts')
<script>
$('#source').select2({
    placeholder:'Select a league to move a player from'
});
$('#to').select2({
    placeholder: "Select a league to move a player to"
});
$('#source_player').parents('.form-group').hide();
$('#to').parents('.form-group').hide();
$('#to_team').parents('.form-group').hide();
$('#submit').hide();
$('#reset').hide();

$('#source').on('change', function (e) {
    var leagueId = $(this).val();
    if (leagueId != 0) {
        $('#to').parents('.form-group').hide();
        $('#to_team').parents('.form-group').hide();

        $('#source_player').parents('.form-group').fadeIn();
        $('#reset').fadeIn();

        handleSelect2('#source_player', '{{ route('typeahead_members') }}/' + leagueId, 1);

        $('#source_player').on('select2-selecting', function (e) {
            if (!isNaN(e.val)) {
                $(this).val(e.val);
                $('#to').parents('.form-group').fadeIn();
            }
        });

        $('#source_player').on('select2-clearing', function (e) {
            $('#to').parents('.form-group').hide();
            $('#to_team').parents('.form-group').hide();
        });
    } else {
        $('#to').parents('.form-group').hide();
        $('#to_team').parents('.form-group').hide();
        $('#source_player').parents('.form-group').hide();
    }
});

$('#to').on('change', function (e) {
    var leagueId = $(this).val();

    if ($('#source').val() != leagueId && leagueId != 0) {
        $('#to_team').parents('.form-group').fadeIn();
        $.ajax({
            url: '{{ route('manage_load_league_teams') }}',
            type: 'get',
            data: { league_id: leagueId },
            dataType: 'json',
            success: function (resp) {
                $('#to_team').find('option').remove();
                $('#to_team').append('<option value="-1">Select Team</option>');
                $('#to_team').append('<option value="0">No Team</option>');
                $.each(resp, function (idx, val) {
                    $('#to_team').append('<option value="' + idx + '">' + val + '</option>');
                });
            }
        });
    } else {
        $('#to_team').parents('.form-group').hide();
        $('#to').parents('.form-group').fadeIn();
        $('#to').val(0);
    }
});

$('#to_team').on('change', function (e) {
    $('#submit').fadeIn();
});

$('#reset').on('click', function (e) {
    $('#source').select2('val', 0);
    $('#source_player').parents('.form-group').hide();
    $('#to').parents('.form-group').hide();
    $('#to_team').parents('.form-group').hide();
    $('#submit').hide();
    $('#reset').hide();
});
</script>
@endsection
