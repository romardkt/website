<!DOCTYPE html>
<html lang="en-US" style="font-family: sans-serif;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;">
    <head>
        <meta charset="utf-8">
    </head>
    <body style="margin: 10px 0;">
        <h1 style="margin-bottom: 30px;">CUPA Scholarship Submission</h1>
        <p style="margin: 20px 0;">
            A submission has been created for the {{ $title }} scholarship.
        </p>
        <p style="margin: 20px 0;">
            You can view the information and review the document and mark it as accepted <a href="{{ route($route) }}">here</a>.
        </p>
        <p style="margin: 20px 0;">
            Information submitted:
<pre>{!! print_r($data, true) !!}</pre>
        </p>

        @include('emails.footer')
    </body>
</html>
