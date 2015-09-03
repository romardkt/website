<!DOCTYPE html>
<html lang="en-US" style="font-family: sans-serif;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;">
    <head>
        <meta charset="utf-8">
    </head>
    <body style="margin: 10px 0;">
        <h1 style="margin-bottom: 30px;">{{ $leagueName }} {{ $leagueStatus }}</h1>
        <p style="margin: 20px 0;">
            Hello {{ $userName }},
        </p>
        <p style="margin: 20px 0;">
            You have successfully registered for the {{ $leagueName }} league.  Please take the time to sign your waiver, on-line if you are 18 or older, or in paper form if you are younger.  You may find the links below for all the information.
        </p>
        <p style="margin: 20px 0;">
            <ul>
                <li>You may see your league status <a href="{{ route('league_success', [$leagueSlug]) }}">here</a></li>
                <li>You may contact the director(s) <a href="{{ route('league_email', [$leagueSlug]) }}">here</a></li>
                <li>View the teams <a href="{{ route('league_teams', [$leagueSlug]) }}">here</a></li>
                <li>View the league schedule <a href="{{ route('league_schedule', [$leagueSlug]) }}">here</a></li>
            </ul>
        </p>
        <div style="padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; color: #a47e3c; background-color: #fcf8e3; border-color: #fbeed5; color: #c09853;">
            <h4 style="margin-top: 0; margin-bottom: 0; color: inherit;">Payment/Waiver</h4>
            <p style="margin-bottom: 0; margin-top: 0;">If you are not paying or signing your waiver on-line be sure to bring them both with you on the first night.  If you do not you risk not being allowed to play.</p>
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
