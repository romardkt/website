<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>CUPA - Cincinnati Ultimate Players Association</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Cincinnati Ultimate Players Association&#39;s (CUPA) mission is to serve as a regional resource, promoting growth in the sport of Ultimate and instilling Spirit of the Game&#0153; at all levels of play.  CUPA is an Ohio non-profit corporation run solely by volunteers. CUPA is tax exempt under IRS section 501(c)(4).">
        <meta name="author" content="Nick Felicelli">
        <meta name="HandheldFriendly" content="True">
        <meta name="MobileOptimized" content="320">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="cleartype" content="on">

        <meta property="og:image" content="https://cincyultimate.org/img/fb-logo.jpg" />
        <meta property="og:title" content="Cincinnati Ultimate Players Association" />
        <meta property="og:description" content="Cincinnati Ultimate Players Association&#39;s (CUPA) mission is to serve as a regional resource, promoting growth in the sport of Ultimate and instilling Spirit of the Game&#0153; at all levels of play.  CUPA is an Ohio non-profit corporation run solely by volunteers. CUPA is tax exempt under IRS section 501(c)(4)." />

        <meta name="apple-mobile-web-app-title" content="CUPA">

        <meta name="msapplication-TileImage" content="{{ asset('/apple-touch-icon.png') }}">
        <meta name="msapplication-TileColor" content="#ffffff">

        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}"/>
        <link href="{{ elixir('css/cupa.min.css') }}" rel="stylesheet">
        @yield('page-styles')
        <!--<script src="http://maps.googleapis.com/maps/api/js?sensor=true"></script>-->
    </head>
    <body>
        @include('message')
        @include('header')
        <div class="container page-content">
            <!--[if lt IE 9]>
            <div id="ie-message" class="alert alert-warning">You are using an outdated browser, please update to view this web page as intended.  <a href="http://browsehappy.com/" target="_new">More information</a></div>
            <![endif]-->
            @yield('content')
        </div>
        <div class="container footer">
            @include('footer')
        </div>
        <div id="goto-top"><i class="fa fa-lg fa-fw fa-arrow-up"></i></div>
        <script src="{{ elixir('js/cupa.min.js') }}"></script>
        <script>
            var BASE_URL = '{{ route('home') }}/';
        </script>
        @yield('page-scripts')
        @if(App::environment() == 'prod')
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='https://www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-15958619-1');ga('send','pageview');
        </script>
        @endif
    </body>
</html>
