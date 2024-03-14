<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <style>
			@page {
				margin-top: 35px;
				margin-bottom: 20px;
				margin-left: 20px;
				margin-right: 20px;
			}

            @font-face {
                font-family: 'ipamincho';
                font-style: normal;
                font-weight: normal;
                src:url('{{ storage_path('fonts/ipam.ttf')}}');
            }
			
            body {
                font-family: 'ipamincho';
                font-size: 8pt;
                margin: 0px;
            }

			p {
				margin: 0pt;
			}

			p.title {
				font-size: 11.5pt;
				text-align: left;
			}

			hr.title {
				margin: 0px;
				border: none;
				background-color: #808080;
				height: 3pt;
			}

			p.publisher_font {
				font-size: 6.5pt;
			}

			p.list_font, p.list_minus_font {
				font-size: 6.5pt;
			}

			p.list_minus_font {
				color: #F00;
			}
			
			p.sum_font {
				font-size: 7.5pt;
			}

			table, tbody {
				overflow: visible;
			}

			tr.even {
				background-color: #D9D9D9;
			}

			td {
				vertical-align: middle;
				padding-left: 1.5pt;
				padding-right: 1.5pt;
				word-break: break-all;
			}

			td.listhead {
				border: 1px solid;
				background-color: #D9D9D9;
			}

			td.listhead_double {
				border-top: 1px solid;
				border-bottom: 1px solid;
				border-left: 3px double;
				border-right: 1px solid;
				background-color: #D9D9D9;
			}

			td.listfirst {
				border-top: 1px solid;
				border-left: 1px solid;
				border-right: 1px solid;
			}
			
			td.listfirst_double {
				border-top: 1px solid;
				border-left: 3px double;
				border-right: 1px solid;
			}

			td.listmiddle {
				border-left: 1px solid;
				border-right: 1px solid;
			}

			td.listmiddle_double {
				border-left: 3px double;
				border-right: 1px solid;
			}

			td.listlast {
				border-bottom: 1px solid;
				border-left: 1px solid;
				border-right: 1px solid;
			}

			td.listlast_double {
				border-bottom: 1px solid;
				border-left: 3px double;
				border-right: 1px solid;
			}
		
			td.sum {
				border: 1px solid;
			}

			td.sum_double {
				border-top: 1px solid;
				border-bottom: 1px solid;
				border-left: 3px double;
				border-right: 1px solid;
			}

			td.left {
				text-align: left;
			}
	
			td.center {
				text-align: center;
			}

			td.right {
				text-align: right;
			}

			td.height_adjust {
				width: 0.01%;
				padding: 0px;
			}

			.shop_info {
				margin-left: 21px;
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
