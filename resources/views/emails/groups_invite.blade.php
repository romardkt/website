<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h1>User requested invite to CUPA Google Group</h1>

        <p>
            <a href="https://groups.google.com/forum/#!managemembers/cupa_announcements/invite">
                https://groups.google.com/forum/#!managemembers/cupa_announcements/invite
            </a>
        </p>
        <p>
            @if(count($users))
            @foreach($users as $id => $email)
            {{ $email }}

            @if($id % 10 === 0)
            <br/>
            @endif
            @endforeach
            @else
            There are no additions at this time.
            @endif
        </p>
        <p>
<pre>
If you wish to join the CUPA Announcement group, just accept this invite.  This email list allows you to keep up to date on what is going on with CUPA.

You can also follow us on twitter/facebook:
https://twitter.com/cincyultimate
https://www.facebook.com/cincyultimate
</pre>
        </p>
    </body>
</html>
