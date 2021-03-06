<!DOCTYPE html>
<html lang="en-US" style="font-family: sans-serif;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;">
    <head>
        <meta charset="utf-8">
    </head>
    <body style="margin: 10px 0;">
        <h1 style="margin-bottom: 30px;">Password Reset Confirmation</h1>
        <p style="margin: 20px 0;">
            You have successfully reset your password for this account.
        </p>
        <br/>
        <div class="alert-warning" style="padding: 15px;margin-bottom: 20px;border: 1px solid transparent;border-radius: 4px;color: #8a6d3b;background-color: #fcf8e3;border-color: #faebcc;">
            <p style="margin:0;">If you did not change your password please <a style="font-weight: bold; color: #8a6d3b" href="{{ route('reset') }}">change your password</a> to secure your account.  You may also report this unauthorized password reset by emailing <a style="font-weight: bold; color: #8a6d3b" href="mailto:webmaster@cincyultimate.org">webmaster@cincyultimate.org</a></p>
        </div>
        @include('emails.footer')
    </body>
</html>
