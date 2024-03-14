<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <style>
            @font-face {
                font-family: 'mplus-regular';
                font-style: normal;
                font-weight: normal;
                src:url('{{ storage_path('fonts/MPLUSRounded1c-Regular.ttf')}}');
            }

            @font-face {
                font-family: 'mplus-bold';
                font-style: normal;
                font-weight: bold;
                src:url('{{ storage_path('fonts/MPLUSRounded1c-Bold.ttf')}}');
            }


            body {
                font-family: 'mplus-regular', 'mplus-bold';
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
            th {
                line-height: 0.4rem;
            }

            table {
                border-collapse: collapse;
                font-size: 10px;
            }

            table.list {
                width: 100%;
            }
            .table-borderless {
                border: 0px solid black;
            }

            .table-borderless td, .table-borderless th {
                border: 0px solid black;
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

            .longtext {
                line-height: 0.4em;
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
