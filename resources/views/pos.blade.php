<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="author" content="StarkLiew">
        <meta name="description" content="">

        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="application-name" content="app">
        <meta name="apple-mobile-web-app-title" content="app">
        <meta name="theme-color" content="#233b69">
        <meta name="msapplication-navbutton-color" content="#233b69">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

        <link rel="icon" type="image/png" sizes="192x192" href="assets/img/app.png">
        <link rel="apple-touch-icon" type="image/png" sizes="192x192" href="assets/img/app.png">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="viewport" content="width=device-width">
        <meta name="mobile-web-app-capable" content="yes">

        <link rel="manifest" href="manifest.json">



        <title>{{ config('app.name') }}</title>

        <!-- <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons" rel="stylesheet"> -->
        <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
        <link href="{{ mix('/css/vue.css') }}" rel="stylesheet">



        <script>
            window.Laravel = {!! json_encode([
                'siteName' => config('app.name'),
                'siteUrl' => config('app.url'),
                'apiUrl' => config('app.url') . '/api'
            ]) !!};
        </script>
    </head>
    <body>
        <div id="app"></div>

        <script src="{{ mix('/pos/app.js') }}"></script>
        <script src="/js/fingerprint2.min.js"></script>
        <script>

            if ('serviceWorker' in navigator && 'PushManager' in window) {
                window.addEventListener('load', function() {
                    navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
                        // Registration was successful
                        console.log('ServiceWorker registration successful with scope: ', registration.scope);
                    }, function(err) {
                        // registration failed :(
                        console.log('ServiceWorker registration failed: ', err);
                    });
                });
            }

        </script>
    </body>
</html>
