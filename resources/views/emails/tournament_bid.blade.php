<!DOCTYPE html>
<html lang="en-US" style="font-family: sans-serif;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;">
    <head>
        <meta charset="utf-8">
    </head>
    <body style="margin: 10px 0;">
        <h1 style="margin-bottom: 30px;">{{ $tournament->display_name }} Bid</h1>
        <p style="margin: 20px 0;">
            Thank you for submitting your bid for the {{ $tournament->display_name }} tournament.  You have been added to our system and will be marked as accepted by the tournament directors when they review your bid submission.
        </p>
        <p style="margin: 20px 0;">
            @if($tournament->use_paypal == 1)
                @if(empty($tournament->paypal))
            <p>
                If you would like to pay via paypal you may do so below.  Just click the button and it will take you to paypal's site to pay for the tournament.
            </p>
            <p>
                The tournament cost is <span style="color: #31708f;">${{ $tournament->cost }}</span>, <a href="{{ route('paypal', [$tournament->id, 'tournament', null, $team->id]) }}">Pay Now!</a>
            </p>
                @else
                <pre>{{ $tournament->paypal }}</pre>
                @endif
            @endif
            @if(!empty($tournament->mail))
            <p>
                If you would like to mail a check in for the payment for the tournament you may mail payments to here:
            </p>
            <pre>{{ $tournament->mail }}</pre>
            @endif
        </p>
        <p style="margin: 20px 0;">
            <p>Here are some links for you:</p>
            <ul style="list-style: none;">
                <li><a href="{{ route('tournament', [$tournament->name, $tournament->year]) }}">{{ $tournament->display_name }} Website</a></li>
                <li><a href="{{ route('tournament_contact', [$tournament->name, $tournament->year]) }}">{{ $tournament->display_name }} Contact Information</a></li>
                <li><a href="{{ route('about') }}">Learn about CUPA</a></li>
            </ul>
        </p>
        <p style="margin: 20px 0;">
            Check back sometime to see updated information about the {{ $tournament->display_name }} tournament.
        </p>
        @include('emails.footer')
    </body>
</html>
