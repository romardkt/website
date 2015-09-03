@extends('tournament')

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <h1 class="title">2014 Great Lakes Master / Grand Masters Regional Championships</h1>
        <p>Cincinnati is hosting the 2014 Great Lakes Regional Masters tournament at the Scinny Tournament location.</p>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        <dl class="dl-horizontal">
            <dt>Tournament Director:</dt>
            <dd>You can email {{ secureEmail('wilkerd@cintas.com', 'Dale') }}</dd>
            <dt>Regional Coordinator:</dt>
            <dd>You can email {{ secureEmail('ultimate711@gmail.com', 'Alexander') }}</dd>
            <br/>
            <dt>Grand Masters:</dt>
            <dd><a href="http://play.usaultimate.org/events/Great-Lakes-Grand-Masters-Regionals">http://play.usaultimate.org/events/Great-Lakes-Grand-Masters-Regionals</a></dd>
            <dt>Masters:</dt>
            <dd><a href="http://play.usaultimate.org/events/Great-Lakes-Masters-Regionals">http://play.usaultimate.org/events/Great-Lakes-Masters-Regionals</a></dd>
        </dl>
    </div>
</div>
@endsection

@section('page-scripts')
@endsection
