<!DOCTYPE html>
<html lang="en-US" style="font-family: sans-serif;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;">
    <head>
        <meta charset="utf-8">
    </head>
    <body style="margin: 10px 0;">
        <h1 style="margin-bottom: 30px;">CUPA Account Activation</h1>
        <p style="margin: 20px 0;">
            A request has been made from our system (<a href="http://cincyultimate.org">http://cincyultimate.org</a>) to create a user account.
        </p>
        <p style="margin: 20px 0;">
            The only thing that you have to do to activate your account is to click on the button to activate your account.  Your
            account will be activated and you will automatically be logged in.
        </p>
        <p style="margin: 20px 0;">
            <a style="display: inline-block;padding: 6px 12px;margin-bottom: 0;font-size: 14px;font-weight: normal;line-height: 1.42857143;text-align: center;white-space: nowrap;vertical-align: middle;cursor: pointer;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;background-image: none;border: 1px solid transparent;border-radius: 4px;text-decoration: none;color: #fff;background-color: #5cb85c;border-color: #4cae4c;" href="{{ route('activate', array($code)) }}">Activate Account</a>
        </p>
        <br/>
        <div class="alert-warning" style="padding: 15px;margin-bottom: 20px;border: 1px solid transparent;border-radius: 4px;color: #8a6d3b;background-color: #fcf8e3;border-color: #faebcc;">
            <p style="margin:0;">If you did not request this please just ignore this email and the account will be cleared out after a while.</p>
        </div>

        @include('emails.footer')
    </body>
</html>
