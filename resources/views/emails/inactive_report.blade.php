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

        <p style="margin: 20px 0;">
            <br/>
            <hr style="height: 0;-moz-box-sizing: content-box;box-sizing: content-box;margin-top: 20px;margin-bottom: 20px;border: 0;border-top: 1px solid #eee;"/>
            <em>
                This is an automated message from <a href="https://cincyultimate.org">https://cincyultimate.org</a>.<br/>
                You may contact the webmaster at <a href="mailto:webmaster@cincyultimate.org">webmaster@cincyultimate.org</a>
            </em>
        </p>
    </body>
</html>
