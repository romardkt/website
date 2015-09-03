@extends('layouts.master')

@section('content')
@include('layouts.page_header')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Tournaments</h2>
    </div>
</div>
<hr/>
<div class="row tournaments">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8">
        <div class="list-group">
            <?php $usOpen = false; ?>
            @foreach($tournaments as $tournament)

            @if($tournament->start < (new DateTime('2015-07-02'))->format('Y-m-d') && !$usOpen)
            <?php $usOpen = true; ?>
            <a href="http://play.usaultimate.org/events/US-Open-Ultimate-Championships-2015/" class="list-group-item">
                <h3>US Open 2015</h3>
                <em>{{ (new DateTime('2015-07-02'))->format('M j Y')}} -
                {{ (new DateTime('2015-07-05'))->format('M j Y')}}</em>
                <p>
                    The U.S. Open is one of the major events of the 2015 Triple Crown Tour, with bid priority going to teams that finished 1st-4th at the prior National Championships, as well as to international teams.
                </p>
            </a>
            @endif

            <a href="{{ route('tournament', [$tournament->name, $tournament->year]) }}" class="list-group-item">
                <p class="pull-right">{{ ($tournament->is_visible == 1) ? '' : '<span class="text-warning">*** Not Visible to Public ***</span>' }}</p>
                <h3>{{{ $tournament->display_name }}}</h3>
                <em>{{ (new DateTime($tournament->start))->format('M j Y')}} -
                {{ (new DateTime($tournament->end))->format('M j Y')}}</em>
                <p>
                    {{ str_limit(preg_replace("/\s+/", ' ', strip_tags($tournament->description)), 350) }}
                </p>
            </a>
            @if($tournament->name == 'scinny' && in_array($tournament->year, [2014, 2015]))
             <a href="{{ route('tournament_masters_' . $tournament->year) }}" class="list-group-item">
                <p class="pull-right">{{ ($tournament->is_visible == 1) ? '' : '<span class="text-warning">*** Not Visible to Public ***</span>' }}</p>
                <h3>GL G/Masters Regionals {{ $tournament->year }}</h3>
                <em>{{ (new DateTime($tournament->start))->format('M j Y')}} -
                {{ (new DateTime($tournament->end))->format('M j Y')}}</em>
                <p>
                    Regional Qualifier for Great Lakes Grand/Masters for {{ $tournament->year }}
                </p>
            </a>
            @endif
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
@endsection
