<!DOCTYPE html>
<html lang="en-US" style="font-family: sans-serif;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;">
    <head>
        <meta charset="utf-8">
    </head>
    <body style="margin: 10px 0;">
        <h1 style="margin-bottom: 30px;">CUPA Inactive Repory</h1>
        @if(isset($logFile))
        <p style="margin: 20px 0;">
            There were some inactives to report, please check the attached files for details.
        </p>
        @else
        <p style="margin: 20px 0;">
            There is no inactive removals to report.
        </p>

        @endif

        @include('emails.footer')
    </body>
</html>
