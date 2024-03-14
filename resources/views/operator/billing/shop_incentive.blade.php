@extends('operator.billing.shop_incentive_master')

@section('content')
	<div>
		<p class="title">契約インセンティブ計算明細書（回線利用レポート）</p>
		<hr class="title" />
		<div class="clearfix" style="margin-top: 7px;">
			<div class="shop_info">
				<table style="border-collapse: collapse;" cellspacing="0">
					<tr>
						<td style="width: 77px;">
							<p>販売店ID</p>
						</td>
						<td colspan="2">
							<p>{{$shop->code}}</p>
						</td>
						<td></td>
					</tr>
					<tr>
						<td>
							<p>販売店名</p>
						</td>
						<td colspan="2">
							<p>{{$shop->name}}</p>
						</td>
						<td>様</td>
					</tr>
					<tr>
						<td>
							<p>支払い予定日</p>
						</td>
						<td style="width: 98px;">
							<p>{{$targetMonth->format('Y年m月末')}}</p>
						</td>
						<td style="width: 70px;">
							<p>確定分</p>
						</td>
						<td></td>
					</tr>
				</table>
			</div>
			<div class="publisher">
				<table style="border-collapse: collapse;" cellspacing="0">
					<tr style="text-align: right;">
						<td style="width: 38px;"><p class="publisher_font">発行日</p></td>
						<td style="width: 67px;"><p class="publisher_font">{{$nowDate->format('Y年m月d日')}}</p></td>
					</tr>
					<tr style="text-align: right;">
						<td colspan="2"><p>八重洲無線株式会社</p></td>
					</tr>
				</table>
			</div>
		</div>
		<table style="width: 100%; border-collapse: collapse; margin-top: 12px;" cellspacing="0">
			<tr>
				<td class="listhead" style="width: 4%;">
					<p class="list_font">No.</p>
				</td>
				<td class="listhead" style="width: 10%;">
					<p class="list_font">種別</p>
				</td>
				<td class="listhead" style="width: 15%;">
					<p class="list_font">回線ID / 拡販プログラム</p>
				</td>
				<td class="listhead" style="width: 10%;">
					<p class="list_font">対象月</p>
				</td>
				<td class="listhead_double" style="width: 9%;">
					<p class="list_font">利用開始日</p>
				</td>
				<td class="listhead" style="width: 7%;">
					<p class="list_font">利用月数</p>
				</td>
				<td class="listhead" style="width: 7%;">
					<p class="list_font">契約者ID</p>
				</td>
				<td class="listhead" style="width: 25%;">
					<p class="list_font">契約者名</p>
				</td>
				<td class="listhead" style="width: 13%;">
					<p class="list_font">インセンティブ金額</p>
				</td>
				<td class="height_adjust"><p style="height: 15px;" /></td>
			</tr>
		@php
			$itemNumber = 1;
			$lastLineNumber = count($incentiveDetailArray);
			if ($lastLineNumber < $minNumberOfLines) {
				$lastLineNumber = $minNumberOfLines;
			}
		@endphp
		@foreach($incentiveDetailArray as $item)
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
					<p class="list_font">{{$item->data_kind}}</p>
				</td>
				<td class="{{$className}}">
					<p class="list_font">{{$item->line_num}}</p>
				</td>
				<td class="{{$className}}">
					<p class="list_font">{{$item->target_month}}</p>
				</td>
				<td class="{{$className}}_double">
					<p class="list_font">{{$item->start_date}}</p>
				</td>
				<td class="{{$className}} center">
					<p class="list_font">{{$item->use_months}}</p>
				</td>
				<td class="{{$className}}">
					<p class="list_font">{{$item->contractor_id}}</p>
				</td>
				<td class="{{$className}}">
					<p class="list_font">{{$item->contract_name}}</p>
				</td>
				<td class="{{$className}} right">
					<p class="list_font">\{{number_format($item->incentive_price)}}</p>
				</td>
				<td class="height_adjust"><p style="height: 15px;" /></td>
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
				<td class="{{$className}}_double">
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
				<td class="height_adjust"><p style="height: 15px;" /></td>
			</tr>
		@php	$itemNumber++;	@endphp
		@endwhile
			<tr>
				<td class="sum">
					<p class="list_font">&nbsp;</p>
				</td>
				<td class="sum">
					<p class="list_font">&nbsp;</p>
				</td>
				<td class="sum">
					<p class="list_font">&nbsp;</p>
				</td>
				<td class="sum">
					<p class="list_font">&nbsp;</p>
				</td>
				<td class="sum_double">
					<p class="list_font">&nbsp;</p>
				</td>
				<td class="sum">
					<p class="list_font">&nbsp;</p>
				</td>
				<td class="sum">
					<p class="list_font">&nbsp;</p>
				</td>
				<td class="sum right">
					<p class="sum_font">合計</p>
				</td>
				<td class="sum right">
					<p class="sum_font">¥{{number_format($sumIncentivePrice)}}</p>
				</td>
				<td class="height_adjust"><p style="height: 17px;" /></td>
			</tr>
		</table>
		<p style="margin-top: 2px;">＊利用料金の決済ができなかった場合は、対象回線のインセンティブ支払は決済完了まで保留されます。</p>
	</div>
@stop
