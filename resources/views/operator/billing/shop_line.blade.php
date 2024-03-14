@extends('operator.billing.shop_line_master')

@section('content')
	<div>
		<p class="title">回線利用レポート</p>
		<hr class="title" />
		<div class="clearfix" style="margin-top: 10px;">
			<div class="shop_info">
				<table style="border-collapse: collapse;" cellspacing="0">
					<tr>
						<td style="width: 80px;">
							<p>販売店ID</p>
						</td>
						<td style="width: 240px;">
							<p>{{$shop->code}}</p>
						</td>
						<td></td>
					</tr>
					<tr>
						<td>
							<p>販売店名</p>
						</td>
						<td>
							<p>{{$shop->name}}</p>
						</td>
						<td>様</td>
					</tr>
					<tr>
						<td>
							<p>ご利用月</p>
						</td>
						<td>
							<p>{{$targetMonth->format('Y年m月分')}}</p>
						</td>
						<td></td>
					</tr>
				</table>
			</div>
			<div class="publisher">
				<table style="border-collapse: collapse;" cellspacing="0">
					<tr style="text-align: right;">
						<td style="width: 40pt;"><p class="publisher_font">発行日</p></td>
						<td style="width: 72pt;"><p class="publisher_font">{{$nowDate->format('Y/m/d')}}</p></td>
					</tr>
					<tr style="text-align: right;">
						<td colspan="2"><p>八重洲無線株式会社</p></td>
					</tr>
				</table>
			</div>
		</div>
		<table style="width: 100%; border-collapse: collapse; margin-top: 16px;" cellspacing="0">
			<tr>
				<td class="listhead" style="width: 4%;">
					<p class="list_font">No.</p>
				</td>
				<td class="listhead" style="width: 12%;">
					<p class="list_font">回線ID</p>
				</td>
				<td class="listhead" style="width: 11%;">
					<p class="list_font">ご利用月</p>
				</td>
				<td class="listhead" style="width: 28%;">
					<p class="list_font">プランNo</p>
				</td>
				<td class="listhead" style="width: 10%;">
					<p class="list_font">利用開始日</p>
				</td>
				<td class="listhead" style="width: 8%;">
					<p class="list_font">契約者ID</p>
				</td>
				<td class="listhead" style="width: 27%;">
					<p class="list_font">契約者名</p>
				</td>
				<td class="height_adjust"><p style="height: 22px;" /></td>
			</tr>
			@php
				$itemNumber = 1;
				$lastLineNumber = count($userLinePlanArray);
				if ($lastLineNumber < $minNumberOfLines) {
					$lastLineNumber = $minNumberOfLines;
				}
			@endphp
			@foreach($userLinePlanArray as $item)
			@php	$className = "listmiddle";	@endphp
			@if ($itemNumber == 1)
			@php	$className = "listfirst";	@endphp
			@elseif ($itemNumber == $lastLineNumber)
			@php	$className = "listlast";	@endphp
			@endif
			@if (($itemNumber % 2) == 1)
			<tr>
			@else
			<tr class="even">
			@endif
				<td class="{{$className}}">
					<p class="list_font">{{$itemNumber}}</p>
				</td>
				<td class="{{$className}}">
					<p class="list_font">{{$item->voip_line_id}}</p>
				</td>
				<td class="{{$className}}">
					<p class="list_font">{{$targetMonth->format('Y年m月分')}}</p>
				</td>
				<td class="{{$className}}">
					<p class="list_font">{{$item->plan_nums}}</p>
				</td>
				<td class="{{$className}}">
					<p class="list_font">{{$item->start_date}}</p>
				</td>
				<td class="{{$className}}">
					<p class="list_font">{{$item->contractor_id}}</p>
				</td>
				<td class="{{$className}}">
					<p class="list_font">{{$item->contract_name}}</p>
				</td>
				<td class="height_adjust"><p style="height: 22px;" /></td>
			</tr>
			@php	$itemNumber++;	@endphp
			@endforeach
			@while ($itemNumber <= $lastLineNumber)
			@php	$className = "listmiddle";	@endphp
			@if ($itemNumber == 1)
			@php	$className = "listfirst";	@endphp
			@elseif ($itemNumber == $lastLineNumber)
			@php	$className = "listlast";	@endphp
			@endif
			@if (($itemNumber % 2) == 1)
			<tr>
			@else
			<tr class="even">
			@endif
				<td class="{{$className}}">
					<p class="list_font">&nbsp;</p>
				</td>
				<td class="{{$className}}">
					<p class="list_font">&nbsp;</p>
				</td>
				<td class="{{$className}}">
					<p class="list_font">&nbsp;</p>
				</td>
				<td class="{{$className}}">
					<p class="list_font">&nbsp;</p>
				</td>
				<td class="{{$className}}">
					<p class="list_font">&nbsp;</p>
				</td>
				<td class="{{$className}}">
					<p class="list_font">&nbsp;</p>
				</td>
				<td class="{{$className}}">
					<p class="list_font">&nbsp;</p>
				</td>
				<td class="height_adjust"><p style="height: 22px;" /></td>
			</tr>
			@php	$itemNumber++;	@endphp
			@endwhile
		</table>
	</div>
@stop
