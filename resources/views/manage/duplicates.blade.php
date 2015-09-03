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
        <legend>Unpaid List</legend>

        <div class="list-group">
            @foreach($duplicates as $users)
            <div class="list-group-item">
                <h4 class="list-group-item-heading">{{ $users[0]->fullname() }}</h4>
                <p class="list-group-item-text">
                    @foreach($users as $user)
                    <p>
                        <button class="btn btn-warning pull-right use-btn" data-user="{{ $user->id }}">Use This</button>
                        <strong>
                            #{{ $user->id }} -
                            {{ (empty($user->email)) ? 'Minor' : $user->email }} -
                            {{ ($user->parentObj) ? '( Parent: ' . $user->parentObj->fullname() . ' - #' . $user->parentObj->id . ')' : 'No Parent' }}
                        </strong>
                    </p>
                    @if($user->is_active == 0)
                        <span class="label label-danger">Not Active</span> <br class="visible-xs"/> {{ $user->reason }}
                    @else
                    <p>
                        <span class="label label-success">Active</span> &nbsp;<br class="visible-xs"/>
                        <span class="label label-info">Created: {{ convertDate($user->created_at, 'm/d/Y h:i A') }}</span> &nbsp;
                        <span class="label label-info">Activated: {{ convertDate($user->activated_at, 'm/d/Y h:i A') }}</span> &nbsp;
                        <span class="label label-info">Login: {{ convertDate($user->last_login_at, 'm/d/Y h:i A') }}</span> &nbsp;
                    </p>
                    @endif
                    <hr/>
                    @endforeach
                </p>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
<script>
$('.use-btn').on('click', function (e) {
    e.preventDefault();
    var userId = $(this).data('user');

    $.ajax({
        url: '{{ route('manage_duplicates') }}',
        type: 'post',
        data: { _token: '{{ csrf_token() }}', user_id: userId },
        success: function (resp) {
            if (resp.status == 'ok') {
                window.location.reload();
            } else {
                alert(resp.message);
            }
        }
    })
});
</script>
@endsection
