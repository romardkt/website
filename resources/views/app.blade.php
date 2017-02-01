<!DOCTYPE html>
<html>
    <head>
        @include('meta')
        @include('styles')
        @yield('page-styles')
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
        @include('scripts')
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
