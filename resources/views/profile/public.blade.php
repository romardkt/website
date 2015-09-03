@extends('app')

@section('content')
<div class="profile">
    <div class="row">
        <div class="col-xs-12 col-sm-offset-1 col-sm-4 text-center">
            <img class="avatar" src="{{ asset($user->avatar) }}"/>
        </div>
        <div class="col-xs-12 col-sm-6 text-center">
            <h1>{{{ $user->fullname() }}}</h1>
            <h4 class="text-muted">{{{ ($user->profile->nickname) ? 'aka \'' . $user->profile->nickname . '\'' : '' }}}</h4>
            <h4>
                {{{ displayHeight($user->profile->height) }}} tall, {{{ displayAge($user->birthday) }}} years old, played for {{{ displayExperience($user->profile->experience) }}}
                @if($user->profile->level != 'New')
                    up to {{{ displayLevel($user->profile->level) }}}
                @endif
            </h4>
            <h4>

            </h4>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-offset-1 col-sm-10">
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-offset-1 col-sm-10">
            <h3>League Participation</h3>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>League</th>
                        <th>Team</th>
                        <th class="text-center">Record</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user->fetchAllLeagues() as $member)
                    <tr>
                        <td><a href="{{ route('league', [$member->league->slug]) }}">{{{ $member->league->displayName() }}}</a></td>
                        <td>{{{ (isset($member->team->name)) ? $member->team->name : 'Not Assigned' }}}</td>
                        <td class="text-center">{{{ (isset($member->team)) ? $member->team->record->record() : 'N/A' }}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
