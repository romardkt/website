<h1>{{ $user->fullname() }}</h1>

<div class="btn-group">
    @can('is-admin')
    <a class="btn btn-default" href="{{ route('manage_impersonate', [$user->id]) }}"><i class="fa fa-fw fa-lg fa-user"></i> Impersonate</a>
    @if($user->is_active == 1)
    <button class="btn btn-danger"><i class="fa fa-fw fa-lg fa-times"></i> Deactivate</button>
    @else
    <button class="btn btn-success"><i class="fa fa-fw fa-lg fa-check"></i> Activate</button>
    @endif
    @endif

    @can('is-volunteer')
    <br/>
    <hr/>
    @if($user->volunteer)
    <button class="btn btn-danger" onclick="removeVolunteer();"><i class="fa fa-fw fa-lg fa-trash"></i> Remove from Volunteers</button>
    @else
    <h4>Not a Volunteer</h4>
    @endif
    @endif
</div>


<script>
@if($user->volunteer)
function removeVolunteer() {
    $.ajax({
        url: '{{ route('manage_volunteer_remove', $user->volunteer->id) }}',
        type: 'get',
        success: function (resp) {
            reloadVolunteer('{{$user->id}}');
        }
    });
}

@endif

function reloadVolunteer(userId) {
    $.ajax({
        url: '{{ route('manage_users_detail') }}',
        type: 'post',
        data: { _token: '{{ csrf_token() }}', user_id: userId },
        success: function (resp) {
            $('#data').html(resp);
            $('#data').fadeIn();
        }
    });
}
</script>
