@extends('app')

@section('content')
@include('page_header')
<div class="team">
    <div class="row team">
        <div class="col-xs-12 col-sm-4 col-md-3 team-logo"><img src="{{ asset($team->logo) }}" alt="Team logo"/></div>
        <div class="col-xs-12 col-sm-8 col-md-9 team-title">
            <p>{{ $team->display_name }}</p>
            <small>Captain(s):
            @foreach ($team->captains() as $captain)
            @if($team->override_email === null)
            {!! secureEmail($captain->user->email, $captain->user->fullname()) !!}
            @else
            {!! secureEmail($team->override_email,  $captain->user->fullname()) !!}
            @endif
            @endforeach
            @if(!isset($captain))
            Unknown
            @endif
            </small>
            <div class="date">
                @if($team->begin !== null)
                Founded in {{ ($team->begin === null) ? 'Unknown' : $team->begin }}
                @if($team->end !== null)
                (Last Season {{ $team->end }})
                @endif
                @endif
            </div>
            <div class="social">
                @if($team->website !== null)
                <a class="web" href="{{ $team->website }}" rel="noopener noreferrer" target="_blank" title="Website">
                    <span class="fa-stack fa-lg">
                        <i class="fa fa-circle fa-stack-2x"></i>
                        <i class="fa fa-home fa-stack-1x fa-inverse"></i>
                    </span></a>
                @endif
                @if($team->facebook)
                <a class="facebook" href="{{ $team->facebook }}" rel="noopener noreferrer" target="_blank" title="Facebook">
                    <span class="fa-stack fa-lg">
                        <i class="fa fa-circle fa-stack-2x"></i>
                        <i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
                    </span></a>
                @endif
                @if($team->twitter)
                <a class="twitter" href="{{ $team->twitter }}" rel="noopener noreferrer" target="_blank" title="Twitter">
                    <span class="fa-stack fa-lg">
                        <i class="fa fa-circle fa-stack-2x"></i>
                        <i class="fa fa-twitter fa-stack-1x fa-inverse"></i>
                    </span></a>
                @endif
            </div>
        </div>
        <div class="col-xs-12 col-sm-offset-1 col-sm-10 description">
            <hr/>
            {!! $team->description !!}
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
@endsection
