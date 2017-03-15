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
            @forelse($leagues as $member)
            <tr>
                <td><a href="{{ route('league', [$member->league->slug]) }}">{{ $member->league->displayName() }}</a></td>
                <td>{{ $member->user->fullname() }}</td>
                @if($member->position == 'player')
                <td class="hidden-xs">{{ (isset($member->team->name)) ? $member->team->name : 'Not Assigned' }}</td>
                @else
                <td class="hidden-xs"><span class="label label-info">On Waitlist</span></td>
                @endif
                <td class="text-center hidden-xs">{{ (isset($member->team)) ? $member->team->record->record() : 'N/A' }}</td>
                <td class="text-center">
                    <?php
                        if($member->user->gender == 'Female') {
                            $cost = ($member->league->registration->cost_female === null) ? $member->league->registration->cost : $member->league->registration->cost_female;
                        } else {
                            $cost = $member->league->registration->cost;
                        }
                    ?>

                    {!! ($member->paid == 0 && $cost > 0) ? '<span class="text-danger"><a class="text-danger" href="' . route('league_success', [$member->league->slug]) . '"><strong>$' . $cost . '</strong></a></span>': '<span class="text-success">$0</span>' !!}</td>
                <td class="text-center">
                    @if($member->user->hasWaiver($member->league->year))
                        <span class="text-success">Yes</span>
                    @else
                        <a href="{{ route('waiver', [$member->league->year, $member->user_id]) }}"><span class="text-danger">No</span></a>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">
                    You have not registered for any leagues yet.
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@include('profile.header')
