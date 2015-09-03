@extends('app')

@section('content')
@include('layouts.page_header')
<div class="row">
    <div class="col-xs-12 text-center">
        <h2 class="page">Board Meeting Minutes</h2>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <table class="table table-condensed table-hover">
            <thead>
                <tr>
                    <th class="col-xs-3">When</th>
                    <th class="col-xs-3">Where</th>
                    <th class="col-xs-2 text-center">Download</th>
                    @if($isAuthorized['editor'])
                    <th class="col-xs-3">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($minutes as $minute)
                <tr>
                    <td>
                        {{{ (new DateTime($minute->start))->format('F d Y') }}}<br/>
                        <span class="text-muted">
                            {{{(new DateTime($minute->start))->format('h:i') }}} - {{{ (new DateTime($minute->end))->format('h:i A') }}}
                        </span>
                    </td>
                    <td>{{{ $minute->location->name }}}</td>
                    <td class="text-center">{{ ($minute->pdf === null) ? 'Not Available' : '<a class="btn btn-default" href="' . route('about_minutes_download', array($minute->id)) . '" title="Download Minutes"><i class="fa fa-fw fa-lg fa-download"></i></a>'}}</td>
                    @if($isAuthorized['editor'])
                    <td>
                        <a class="btn btn-default" title="Edit Minutes" href="{{ route('about_minutes_edit', array($minute->id)) }}"><i class="text-info fa fa-lg fa-fw fa-edit"></i></a>
                        <a class="btn btn-default" title="Remove Minutes" onclick="return confirm('Are you sure you want to delete this meeting minutes?');" href="{{ route('about_minutes_remove', array($minute->id)) }}"><i class="text-danger fa fa-lg fa-fw fa-trash-o"></i></a>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
