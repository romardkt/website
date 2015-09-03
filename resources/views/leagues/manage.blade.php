@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">{{ $league->displayName() }} Manage Players</h2>
    </div>
</div>
@include('leagues.header')
<div class="row">
    <div class="col-xs-12">
        <form class="form" method="post" role="form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <div class="form-group">
                {{ Form::hidden('add_players', 'Add Players') }}
                {{ Form::label('Add players to league') }}
                {{ Form::hidden('players', $initial, ['id' => 'add_players']) }}
                <span class="help-block">Start by typing a players name</span>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Add Players</button>
            </div>
        </form>
    </div>
</div>
<hr/>
<div class="row manage">
    <div class="col-xs-12" id="players">
        <div class="row">
            <div class="col-xs-6">
                <h2>Manage Players</h2>
            </div>
            <div class="col-xs-6">
                <input type="text" placeholder="Filter Players" class="search form-control"/>
            </div>
        </div>
        <table class="table" role="table">
            <thead>
                <tr>
                    <th>Player</th>
                    <th>Current Team</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="list">
                @foreach($members as $member)
                <tr>
                    <td class="name">{{ $member->user->fullname() }}</td>
                    <td class="team">{{ (empty($member->team->name)) ? 'No Team' : $member->team->name }}</td>
                    <td class="text-center">
                        @if(empty($member->team->name))
                        <button class="add-member-btn btn btn-primary" data-member="{{ $member->id }}">Set Team</button>
                        @else
                        <button class="remove-member-btn btn btn-default" data-member="{{ $member->id }}">Clear Team</button>
                        @endif
                        <button class="delete-member-btn btn btn-danger" data-member="{{ $member->id }}">Remove</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="modal fade" id="select-team" tabindex="-1" role="dialog" aria-labelledby="addLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Select a Team</h4>
                </div>
                <div class="modal-body">
                    <form method="post" id="add-team-form">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <input type="hidden" name="add" value="Add"/>
                        <input type="hidden" name="member_id" id="member_id" value=""/>
                        <input type="hidden" name="team_id" id="team_id" value=""/>
                    </form>
                    <div class="list-group">
                        @forelse($teams as $team)
                        <div class="list-group-item text-center select-team">
                            <h4 class="list-group-item-heading team-name" data-team="{{ $team->id }}">{{ $team->name }}</h4>
                        </div>
                        @empty
                        <div class="list-group-item text-center">
                            <h4 class="list-group-item-heading">There are no teams created yet. <a href="{{ route('league_teams', [$league->slug]) }}">Create one</a></h4>
                        </div>
                        @endforelse
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
<script>
var options = {
  valueNames: [ 'name', 'team' ],
  page: 400
};
var playerList = new List('players', options);
var addMember = null;

$('.list').on('click', '.add-member-btn', function(e) {
    e.preventDefault();
    $('#member_id').val($(this).data('member'));
    $('#select-team').modal('show');
    addMember = $(this);
});

$('.list').on('click', '.remove-member-btn',  function(e){
    e.preventDefault();
    var memberId = $(this).data('member');
    var self = $(this);

    $.ajax({
        type: 'POST',
        url: '{{ route('league_manage', [$league->slug]) }}',
        data: { _token: '{{ csrf_token() }}', remove: 'Remove', member_id: memberId },
        dataType: 'json',
        success: function(resp) {
            if(resp.success === 'ok') {
                self.removeClass('remove-member-btn');
                self.removeClass('btn-default');
                self.addClass('add-member-btn');
                self.addClass('btn-primary');
                self.text('Set Team');

                self.parent().prev('.team').text('No Team');

                self.hide().fadeIn('slow');
                self.parent().prev('.team').hide().fadeIn('slow');
            }
        }
    });
});

$('.team-name').on('click', function(e){
    e.preventDefault();
    $('#team_id').val($(this).data('team'));
    var team = $(this).text();

    $.ajax({
        type: 'POST',
        url: '{{ route('league_manage', [$league->slug]) }}',
        data: $('#add-team-form').serialize(),
        dataType: 'json',
        success: function(resp) {
            if(resp.success === 'ok') {
                addMember.removeClass('add-member-btn');
                addMember.removeClass('btn-primary');
                addMember.addClass('remove-member-btn');
                addMember.addClass('btn-default');
                addMember.text('Clear Team');

                $('#select-team').modal('hide');

                addMember.parent().prev('.team').text(team);

                addMember.hide().fadeIn('slow');
                addMember.parent().prev('.team').hide().fadeIn('slow');
            }
        }
    });
});

$('.delete-member-btn').on('click', function(e){
    e.preventDefault();
    if(confirm('Are you sure, this cannot be undone?')) {
        var memberId = $(this).data('member');
        var self = $(this);
        $.ajax({
            type: 'POST',
            url: '{{ route('league_manage', [$league->slug]) }}',
            data: { _token: '{{ csrf_token() }}', delete: 'Delete', member_id: memberId },
            dataType: 'json',
            success: function(resp) {
                if(resp.success === 'ok') {
                    self.parents('tr').fadeOut('slow')
                }
            }
        });
    }
});

handleSelect2('#add_players', '{{ route('typeahead_users') }}', 15);
</script>
@endsection
