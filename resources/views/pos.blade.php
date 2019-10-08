<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="author" content="StarkLiew">
        <meta name="description" content="">

        <title>{{ config('app.name') }}</title>

        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons" rel="stylesheet">
        <link href="{{ mix('/css/app.css') }}" rel="stylesheet">

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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fingerprintjs2/2.1.0/fingerprint2.min.js"></script>
        <script>
            (function() {
                  if('serviceWorker' in navigator) {
                    navigator.serviceWorker.register('/service-worker.js')
                  }


                })();


        </script>
    </body>
</html>
