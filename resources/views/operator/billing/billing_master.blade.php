<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <style>
			@page {
				margin-top: 45px;
				margin-bottom: 35px;
				margin-left: 35px;
				margin-right: 35px;
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
				margin: 0px;
			}

			p.title {
				font-size: 16pt;
				text-align: center;
			}

			hr.title {
				margin: 0px;
				border: none;
				background-color: #808080;
				height: 4px;
			}

			p.publisher_font {
				font-size: 10pt;
			}

			p.greeting_font {
				font-size: 12pt;
			}

			p.list_font, p.list_minus_font {
				font-size: 9pt;
			}

			p.list_minus_font {
				color: #F00;
			}

			.va_middle {
				vertical-align: middle;
			}

			.remarks_frame {
				border: 1px solid;
				margin-top: 10px;
				margin-right: 16px;
				height: 160px;
				padding: 4px;
			}

			.remarks {
				font-size: 9pt;
			}

			table, tbody {
				overflow: visible;
			}

			td {
				vertical-align: middle;
				padding-left: 2.5px;
				padding-right: 2.5px;
			}

			td.listhead, td.bank {
				border: 1px solid;
				background-color: #D9D9D9;
				word-break: break-all;
			}

			td.listvalue {
				border: 1px solid;
				word-break: break-all;
			}

			td.height_adjust {
				width: 0.01%;
				padding: 0px;
			}

			.billing_address {
				padding-top: 20px;
				float: left;
				text-align: left;
				white-space: nowrap;
			}

			.underline {
				border-bottom-style: solid;
				border-bottom-width: 1px;
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

			.padding_tb3 {
				padding-top: 3px;
				padding-bottom: 3px;
			}

		</style>
	</head>
    <body>
        <div class="container-fluid">
			@yield('content')
        </div>
    </body>
</html>
