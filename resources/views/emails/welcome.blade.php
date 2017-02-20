<!DOCTYPE html>
<html lang="en-US" style="font-family: sans-serif;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;">
    <head>
        <meta charset="utf-8">
    </head>
    <body style="margin: 10px 0;">
        <h1 style="margin-bottom: 30px;">Welcome to the CUPA Website</h1>
        <p style="margin: 20px 0;">
            Thank you for requesting and activating your account.  You are now ready to start registering for leagues, helping out and becoming a volunteer, help contribute back to CUPA.
        </p>
        <p style="margin: 20px 0;">
            <p>Here are some things that you can do:</p>
            <ul style="list-style: none;">
                <li><a href="{{ route('profile') }}">Update your profile (change password)</a></li>
                <li><a href="{{ route('profile_minors') }}">Add a minor to your account</a></li>
                <li>Sign up for an <a href="{{ route('leagues') }}">adult league</a> or a <a href="{{ route('youth_leagues') }}">youth league</a></li>
                <li><a href="{{ route('about_board') }}">See the board memebers</a></li>
            </ul>
        </p>
        <p style="margin: 20px 0;">
            Check back often as the website is constantly updated with new leagues, volunteer opportunities, tournaments, pickups, etc.
        </p>
        @include('emails.footer')
    </body>
</html>
