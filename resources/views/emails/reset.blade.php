<!DOCTYPE html>
<html lang="en-US" style="font-family: sans-serif;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;">
    <head>
        <meta charset="utf-8">
    </head>
    <body style="margin: 10px 0;">
        <h1 style="margin-bottom: 30px;">CUPA Password Reset</h1>
        <p style="margin: 20px 0;">
            A request has been made from our system (<a href="http://cincyultimate.org">http://cincyultimate.org</a>) to reset your password for your account: {{ $email }}.
        </p>
        <p style="margin: 20px 0;">
            To reset your password please click on the button below and follow the directions on the page.  Once done you will be logged in to the system and your password will have been reset to the one that you had entered.
        </p>
        <p style="margin: 20px 0;">
            <a style="display: inline-block;padding: 6px 12px;margin-bottom: 0;font-size: 14px;font-weight: normal;line-height: 1.42857143;text-align: center;white-space: nowrap;vertical-align: middle;cursor: pointer;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;background-image: none;border: 1px solid transparent;border-radius: 4px;text-decoration: none;color: #fff;background-color: #5cb85c;border-color: #4cae4c;" href="{{ route('do_reset', array($code)) }}">Reset Your Password</a>
        </p>
        <br/>
        <div class="alert-warning" style="padding: 15px;margin-bottom: 20px;border: 1px solid transparent;border-radius: 4px;color: #8a6d3b;background-color: #fcf8e3;border-color: #faebcc;">
            <p style="margin:0;">If you did not request this please just ignore this email and your password will not be changed.</p>
        </div>

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
