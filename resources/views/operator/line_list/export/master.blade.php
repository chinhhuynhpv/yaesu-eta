<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <style>
            @font-face {
                font-family: 'yuji';
                font-style: normal;
                font-weight: normal;
                src:url('{{ storage_path('fonts/YujiSyuku-Regular.ttf')}}');
            }

            body {
                font-family: 'yuji';
                font-size: 10px;
            }

            hr {
                height:2px;
                border-width:0;
                color:gray;
                background-color:gray;
            }

            table, td, th {
                border: 1px solid black;
            }

            table {
                border-collapse: collapse;
                font-size: 10px;
            }

            .border-black {
                border: 1px solid #000 !important;
            }

            .border-left-black {
                border-left: 1px solid #000 !important;
            }

            .border-bottom-black {
                border-bottom: 1px solid #000 !important;
            }

            .display-inline-block {
                display: inline-block
            }

            .border-right-none {
                border-right: none !important;
            }

            .border-bottom-none {
                border-bottom: none !important;
            }

            .border-top-none {
                border-top: none !important;
            }

            .td-width-left {
                width: 200px
            }

            .td-width-right {
                width: 100px
            }

            .margin-top-heading {
                margin-top: 10px;
            }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            @yield('content')
        </div>
    </body>
</html>
