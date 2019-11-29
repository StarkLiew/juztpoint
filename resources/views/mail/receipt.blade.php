<html>
    <head>
         <meta charset="utf-8">
         <meta http-equiv="X-UA-Compatible" content="IE=edge">
         <meta name="viewport" content="width=device-width, initial-scale=1">
         <title>Receipt</title>
         <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons" rel="stylesheet">
         <script defer src="{{ mix('receipt/app-client.js') }}"></script>
    </head>
    <style>

@page {
    size: 80mm auto;
    margin: 5mm;
}


    * {
        width: 70mm;
        height: auto;
        padding: 0;
        margin: 0;
        list-style-type: none;
        font-family: "arial";
        font-size: 8px;
    }

    .stamp {

        font-weight: bold;
        font-size: 30px;
        position: fixed;
        left: -12px;
        top: -12px;
        text-transform: uppercase;
        transform: rotate(-45deg);

    }

    .title {
        text-align: center;
        font-weight: bold;
    }

    .caption {
        font-weight: bold;
    }

    table th {
        border: none;
        border-bottom: 1px dashed #000;
        padding: 1px;
        text-align: right;

    }

    table tr td {
        border: none;
        border-bottom: none;
        border-right: none;
        padding: 1px;
        text-align: right;

    }

    td.left,
    th.left {
        text-align: left;
    }

    td.line {
        border-top: 1px dashed #000;
    }



    </style>
    <body>

        <div id="app">


             {!! ssr('receipt/app-server.js')->context('data', $data)->render() !!}



        </div>
    </body>
</html>
