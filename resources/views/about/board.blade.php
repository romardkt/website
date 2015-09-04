@extends('app')

@section('content')
@include('page_header')

<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <div class="row">
            <div class="col-xs-12 text-center">
                <h2 class="page">Board Members</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 col-lg-3 hidden-xs">
                <ul class="nav nav-pills nav-stacked officer-navbar">
                @foreach($members as $id => $member)
                    <li>
                        <a href="#{{ $member->user->fullname() }}">
                            <h4>{{ $member->position->name }}</h4>
                            {{ $member->user->fullname() }}
                        </a>
                        <hr/>
                    </li>
                @endforeach
                </ul>
            </div>
            <div class="col-sm-8 col-lg-9 board-member">
            @foreach($members as $id => $member)
                <a name="{{ $member->user->fullname() }}"></a>
                <div class="row">
                    <div class="col-xs-12 picture">
                        <img src="{{ asset($member->image) }}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 title">
                        <h3>{{ $member->user->fullName() }}</h3>
                        <p class="text-danger">{{ $member->position->name }}<p>
                        <p><small class="text-muted">since {{ (new DateTime($member->started))->format('M d Y') }}</small></p>
                        @if($member->position->email !== null)
                        {!! secureEmail($member->position->email) !!}
                        @else
                        <p>&nbsp;</p>
                        @endif
                        <hr/>
                    </div>
                </div>
                @can('is-manager')
                <div class="actions">
                    <div class="btn-group">
                        <a class="btn btn-default" href="{{ route('about_board_edit', array($member->id)) }}"><i class="text-info fa fa-edit fa-fw fa-lg"></i></a>
                        <a title="Remove Officer" class="btn btn-default" onclick="return confirm('Are you sure?');" href="{{ route('about_board_remove', array($member->id)) }}"><i class="text-danger fa fa-trash-o fa-fw fa-lg"></i></a>
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-xs-12 description">
                        {!! $member->description !!}
                        @if(!empty($member->description))
                        <hr/>
                        @endif
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    </div>
</div>

@endsection
