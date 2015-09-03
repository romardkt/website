<!DOCTYPE html>
<html lang="en-US" style="font-family: sans-serif;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;">
    <head>
        <meta charset="utf-8">
    </head>
    <body style="margin: 10px 0;">
        <h1 style="margin-bottom: 30px;">{{ $event->title }} Sign up</h1>
        <p style="margin: 20px 0;">
            Thank you for signing up to help out with this event.  All the information is in this email about the date, time, and place.  You should be hearing from one of the event contacts as it gets closer to the event date.  If you would like more information or need to contact someone you may email one of the event contacts below:
        </p>
        <p style="margin: 20px 0;">
            <ul>
            @foreach($event->contacts()->get() as $contact)
            <?php $user = $contact->user()->first(); ?>
                <li><a href="mailto:{{ (empty($event->email_override)) ? $user->email : $event->email_override }}?subject={{ $event->title }} Information">{{ $user->fullname() }}</a></li>
            @endforeach
            </ul>
        </p>
        <p style="margin: 20px 0;">
            Here is the event information:
            <ul>
                <li><strong>Name:</strong> {{ $event->title }}</li>
                <li><strong>Start:</strong> {{ (new DateTime($event->start))->format('M d Y h:i A') }}</li>
                <li><strong>End:</strong> {{ (new DateTime($event->end))->format('M d Y h:i A') }}</li>
                <li><strong>Location:</strong><br/> {{ $event->location()->first()->address() }}</li>
                <li><strong>Information:</strong><br/> {{ $event->information }}</li>
            </ul>
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
