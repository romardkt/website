<!DOCTYPE html>
<html lang="en-US" style="font-family: sans-serif;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;">
    <head>
        <meta charset="utf-8">
    </head>
    <body style="margin: 10px 0;">
        <h1 style="margin-bottom: 30px;">CUPA League Contact Form</h1>
        <p style="margin: 20px 0;">
            This message was sent from the CUPA website by:<br/>
            {{ $data['name'] }} ({{ $data['from'] }})
        </p>
        @if(App::environment() != 'prod')
        <p style="margin: 20px 0;">
            @foreach($emails as $email)
            BCC: {{ $email }}<br/>
            @endforeach
        </p>
        @endif
        <h4>Message:</h4>
        <p style="margin: 20px 0;">
            {!! $data['body'] !!}
        </p>
    </body>
</html>
