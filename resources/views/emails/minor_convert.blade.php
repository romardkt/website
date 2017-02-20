<!DOCTYPE html>
<html lang="en-US" style="font-family: sans-serif;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;">
    <head>
        <meta charset="utf-8">
    </head>
    <body style="margin: 10px 0;">
        <h1 style="margin-bottom: 30px;">CUPA convert minor accounts</h1>

        <p style="margin: 20px 0;">
           Hello {{$parent->fullname()}},
        </p>

        <p style="margin: 20px 0;">
           You have some minor accounts that are now 18 years of age and can be moved into their own account if you would like.
           All you would need is an email to use for the new account.
        </p>

        <p style="margin: 20px 0;">
          <p>Here are the minors that are affected:</p>
          <ul>
            @foreach($minors as $minor)
            <li>{{$minor->fullname()}}</li>
            @endforeach
          </ul>
        </p>

        <p style="margin: 20px 0;">
            You can click here to see and act on the minors:<br/>
            <a href="{{Config::get('app.url')}}/profile/minors">{{Config::get('app.url')}}/profile/minors</a>
        </p>

        <p style="margin: 20px 0;">
            Thanks.
        </p>
        @include('emails.footer')
    </body>
</html>
