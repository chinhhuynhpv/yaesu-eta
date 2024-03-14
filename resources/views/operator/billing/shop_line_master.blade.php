<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <style>
			@page {
				margin-top: 40px;
				margin-bottom: 30px;
				margin-left: 30px;
				margin-right: 30px;
			}

            @font-face {
                font-family: 'ipamincho';
                font-style: normal;
                font-weight: normal;
                src:url('{{ storage_path('fonts/ipam.ttf')}}');
            }
			
            body {
                font-family: 'ipamincho';
                font-size: 11.5pt;
                margin: 0px;
            }

			p {
				margin: 0pt;
			}

			p.title {
				font-size: 16pt;
				text-align: left;
			}

			hr.title {
				margin: 0px;
				border: none;
				background-color: #808080;
				height: 4pt;
			}

			p.publisher_font {
				font-size: 10pt;
			}

			p.list_font, p.list_minus_font {
				font-size: 9pt;
			}

			p.list_minus_font {
				color: #F00;
			}

			table, tbody {
				overflow: visible;
			}

			tr.even {
				background-color: #D9D9D9;
			}

			td {
				vertical-align: middle;
				padding-left: 2pt;
				padding-right: 2pt;
				word-break: break-all;
			}

			td.listhead {
				border: 1px solid;
				background-color: #D9D9D9;
			}

			td.listfirst {
				border-top: 1px solid;
				border-left: 1px solid;
				border-right: 1px solid;
			}

			td.listmiddle {
				border-left: 1px solid;
				border-right: 1px solid;
			}

			td.listlast {
				border-bottom: 1px solid;
				border-left: 1px solid;
				border-right: 1px solid;
			}

			td.height_adjust {
				width: 0.01%;
				padding: 0px;
			}

			.shop_info {
				margin-left: 30px;
				float: left;
				text-align: left;
			}

			.publisher {
				float: right;
				text-align: right;
			}

			.clearfix:after {
				content: "";
				display: block;
				clear: both;
			}

		</style>
	</head>
    <body>
        <div class="container-fluid">
			@yield('content')
        </div>
    </body>
</html>
