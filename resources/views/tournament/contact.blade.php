@extends('layouts.tournament')

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @if($isAuthorized['manager'])
        <div class="pull-right">
        </div>
        @endif
        <h1 class="title">{{{ $tournament->display_name }}} Contacts</h1>

        <dl class="contacts">
        <?php $count = count($tournament->contacts); ?>
        @foreach($tournament->contacts as $i => $contact)
            @if($isAuthorized['manager'])
            <div class="pull-right">
                <div class="btn-group">
                @if($i != 0)
                    <a class="btn btn-default" title="Move up" href="{{ route('tournament_contact_order', [$contact->id, 'up']) }}"><i class="fa fa-fw fa-lg fa-arrow-up"></i></a>
                @endif
                @if($i < $count - 1)
                    <a class="btn btn-default" title="Move down" href="{{ route('tournament_contact_order', [$contact->id, 'down']) }}"><i class="fa fa-fw fa-lg fa-arrow-down"></i></a>
                @endif
                    <a class="btn btn-danger" onclick="return confirm('Are you sure you want to remove this contact?');" title="Remove contact" href="{{ route('tournament_contact_remove', [$contact->id]) }}"><i class="fa fa-fw fa-lg fa-trash-o"></i></a>
                </div>
            </div>
            @endif
            <dt>{{{ ucwords(str_replace('_', ' ', $contact->position)) }}}:</dt>
            <dd>{{ secureEmail($contact->user->email, $contact->user->fullname()) }}</dd>
        @endforeach
        </dl>
    </div>
</div>
<div class="row tournament-feed">
</div>
@endsection

@section('page-scripts')
@endsection
