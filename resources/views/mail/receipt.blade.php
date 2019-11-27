<html>
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Receipt</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons" rel="stylesheet">
     <script defer src="{{ mix('receipt/app-client.js') }}"></script>

    </head>
    <body>
        <div id="app">
            {!! ssr('receipt/app-server.js')->render() !!}

        </div>
    </body>
</html>
