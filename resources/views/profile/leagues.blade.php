@section('profile_content')
<div class="row">
    <div class="col-xs-12">
        <table class="table table-hover table-responsive">
            <thead>
                <tr>
                    <th>League</th>
                    <th>Player</th>
                    <th class="hidden-xs">Team</th>
                    <th class="text-center hidden-xs">Record</th>
                    <th class="text-center">Owed</th>
                    <th class="text-center">Waiver</th>
                </tr>
            </thead>
            <tbody>
            @foreach($leagues as $member)
            <tr>
                <td><a href="{{ route('league', [$member->league->slug]) }}">{{ $member->league->displayName() }}</a></td>
                <td>{{ $member->user->fullname() }}</td>
                <td class="hidden-xs">{{ (isset($member->team->name)) ? $member->team->name : 'Not Assigned' }}</td>
                <td class="text-center hidden-xs">{{ (isset($member->team)) ? $member->team->record->record() : 'N/A' }}</td>
                <td class="text-center">{!! ($member->paid == 0 && $member->league->registration->cost > 0) ? '<span class="text-danger"><a class="text-danger" href="' . route('league_success', [$member->league->slug]) . '"><strong>$' . $member->league->registration->cost . '</strong></a></span>': '<span class="text-success">$0</span>' !!}</td>
                <td class="text-center">
                    @if($user->hasWaiver($member->league->year))
                    <span class="text-success">Yes</span>
                    @else
                        @if($user->getAge() < 18)
                        <span class="text-danger">No</span>
                        @else
                        <a href="{{ route('waiver', [$member->league->year, $user->id]) }}"><span class="text-danger">No</span></a>
                        @endif
                    @endif
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@include('profile.header')
