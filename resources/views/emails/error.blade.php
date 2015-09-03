<!DOCTYPE html>
<html lang="en-US" style="font-family: sans-serif;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;">
    <head>
        <meta charset="utf-8">
    </head>
    <body style="margin: 10px 0;">
        <h1 style="margin-bottom: 30px;">Website Error {{ $code }} on {{ date('Y-m-d H:i:s')  }}</h1>
        <p style="margin: 20px 0;">
            <h4>URL</h4>
            <pre>
{!! print_r($method, true) !!}
{!! print_r($url, true) !!}
            </pre>
            <h4>LOGGED IN USER</h4>
            <pre>
{!! print_r($user, true) !!}
            </pre>
            <h4>INPUT DATA</h4>
            <pre>
{!! print_r($input, true) !!}
{!! print_r($_SERVER, true) !!}
            </pre>

        </p>
        <p style="margin: 20px 0;">
            <h4>Exception</h4>
            <pre>
{!! $exception !!}
            </pre>
        </p>
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
