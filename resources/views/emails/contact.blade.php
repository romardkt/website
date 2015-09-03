<!DOCTYPE html>
<html lang="en-US" style="font-family: sans-serif;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;">
    <head>
        <meta charset="utf-8">
    </head>
    <body style="margin: 10px 0;">
        <h1 style="margin-bottom: 30px;">CUPA Website Contact Form</h1>
        <p style="margin: 20px 0;">
            This message was sent from the CUPA website by:<br/>
            {{{ $data['from_name'] }}} ({{{ $data['from_email'] }}})
        </p>
        <p style="margin: 20px 0;">
            {{ $data['message'] }}
        </p>
    </body>
</html>
