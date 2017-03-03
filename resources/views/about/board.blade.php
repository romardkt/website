@extends('app')

@section('content')
@include('page_header')

<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <div class="row">
            <div class="col-sm-3 text-center">
                <br/>
                <a class="btn btn-default" href="{{ route('about_board_past')}}">View Past Members</a>
            </div>
            <div class="col-sm-6 text-center">
                <h2 class="page">Current Board Members</h2>
                <hr/>
                <br/>
                <br/>
                <br/>
            </div>
        </div>

        <?php $cnt = 0; ?>
        @foreach($members as $id => $member)
        <div class="row">
            <div class="col-sm-3 board-member-picture<?php echo ($cnt % 2 == 0) ? '' : ' right'; ?>">
                <img src="{{ asset($member->image) }}" alt="{{ $member->user->fullname() }}"/>
                <p class="text-muted">Appointed {{(new DateTime($member->started))->format('M d Y')}}</p>
                <br/>
                @can('edit', $member)
                <div class="btn-group actions">
                    <a class="btn btn-default" href="{{ route('about_board_edit', array($member->id)) }}"><i class="text-info fa fa-edit fa-fw fa-lg"></i></a>
                    @can('delete', $member)
                    <a title="Remove Officer" class="btn btn-default" onclick="return confirm('Are you sure?');" href="{{ route('about_board_remove', array($member->id)) }}"><i class="text-danger fa fa-trash-o fa-fw fa-lg"></i></a>
                    @endif
                </div>
                @endif
            </div>
            <div class="col-sm-9 board-member">
                <h3>{{ $member->user->fullname() }}</h3>
                <p class="text-muted info">
                    {{ $member->position->name }}<br/>
                    @if($member->position->email !== null)
                    {!! secureEmail($member->position->email) !!}
                    @endif
                </p>
                <p class="text-right">{!! $member->description !!}</p>
            </div>
        </div>
        <hr class="board-member-hr"/>
        <?php $cnt++; ?>
        @endforeach
    </div>
</div>

@endsection
