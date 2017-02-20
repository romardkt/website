<!DOCTYPE html>
<html lang="en-US" style="font-family: sans-serif;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;">
    <head>
        <meta charset="utf-8">
    </head>
    <body style="margin: 10px 0;">
        <h1 style="margin-bottom: 30px;">{{ $tournament->display_name }} Bid Submitted</h1>
        <p style="margin: 20px 0;">
            A team has submitted a bid to your tournament.  At your convenience please goto the teams section of the tournament page to accept or deny their request.  If the request is not valid or somehow there is no usable information you may also remove them from the tournament in the teams section of the page.
        </p>
        <p>
            <ul style="list-style: none;">
                <li><a href="{{ route('tournament', [$tournament->name, $tournament->year]) }}">{{ $tournament->display_name }} Website</a></li>
                <li><a href="{{ route('tournament_teams', [$tournament->name, $tournament->year]) }}">{{ $tournament->display_name }} Teams Page</a></li>
            </ul>
        </p>
        <p style="margin: 20px 0;">
            <h4>Team Information:</h4>

            <table cellpadding="5px" cellspacing="0">
                <tr>
                    <td style="width: 150px; font-weight: bold; text-align: right;">Division:</td>
                    <td>{{ ucwords(str_replace('_', ' ', $team->division)) }}</td>
                </tr>
                <tr>
                    <td style="width: 150px; font-weight: bold; text-align: right;">Name:</td>
                    <td>{{ $team->name }}</td>
                </tr>
                <tr>
                    <td style="width: 150px; font-weight: bold; text-align: right;">Location:</td>
                    <td>{{ $team->city . ', ' . $team->state }}</td>
                </tr>
                <tr>
                    <td style="width: 150px; font-weight: bold; text-align: right;">Contact:</td>
                    <td>{{ $team->contact_name . ' ( ' . $team->contact_email . ', ' . $team->contact_phone . ' )' }}</td>
                </tr>
                <tr>
                    <td style="width: 150px; font-weight: bold; text-align: right;">Comments:</td>
                    <td>{{ $team->comments }}</td>
                </tr>
            </table>
        </p>
        @include('emails.footer')
    </body>
</html>
