<!DOCTYPE html>
<html lang="en-US" style="font-family: sans-serif;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;">
    <head>
        <meta charset="utf-8">
    </head>
    <body style="margin: 10px 0;">
        <h1 style="margin-bottom: 30px;">CUPA Coaches Email</h1>
        <p style="margin: 20px 0;">
            This message was sent from the CUPA website by:<br/>
            {{{ $data['name'] }}} ({{{ $data['from'] }}})
        </p>
        @if(App::environment() != 'prod')
<pre>
{{ print_r($coach->toArray(), true) }}
</pre>
        @endif
        <h4>Missing Requirements</h4>
        <ul>
            @foreach($requirements as $reqs => $value)
            @if($value == 0)
            <li>{{ $reqs }}</li>
            @endif
            @endforeach
        </ul>
        <p>You can read about what to do for these <a href="{{ route('youth_coaching') }}">here</a>.
        <p>Once you complete them you may mark them off <a href="{{ route('league_coaches', [$league->slug]) }}">here</a> (You must be logged in for this).

        @if(!empty($data['message']))
        <h4>Message:</h4>
        <p style="margin: 20px 0;">
            {{ $data['message'] }}
        </p>
        @endif
    </body>
</html>
