@extends('operator.billing.billing_master')

@section('content')
	<div>
		<p class="title">ご利用明細書</p>
		<hr class="title" />
		<div class="clearfix" style="margin-top: 10px;">
			<div class="billing_address">
				<table style="border-collapse: collapse; width: 45%;" cellspacing="0">
					<tr>
						<td class="underline padding_tb3" style="width: 95%;">
							<p>〒{{$billing->user->billing_post_number}}</p></td>
						<td style="width: 5%;"></td>
					</tr>
					<tr>
						<td class="underline padding_tb3">
							<p>{{$billing->user->billing_prefectures}}{{$billing->user->billing_municipalities}}{{$billing->user->billing_address}}</p></td>
						<td></td>
					</tr>
					<tr>
						<td class="underline padding_tb3">
							<p>{{$billing->user->billing_building}}</p></td>
						<td></td>
					</tr>
					<tr>
						<td class="underline padding_tb3">
							<p>{{$billing->user->contract_name}}</p></td>
						<td></td>
					</tr>
					<tr>
						<td class="underline padding_tb3">
							<p>{{$billing->user->billing_department}}</p></td>
						<td></td>
					</tr>
					<tr>
						<td class="underline padding_tb3">
							<p>{{$billing->user->billing_manager_name}}</p></td>
						<td>様</td>
					</tr>
				</table>
			</div>
			<div class="publisher">
				<table style="border-collapse: collapse;" cellspacing="0">
					<tr style="text-align: right;">
						<td style="width: 54px;"><p class="publisher_font">発行日</p></td>
						<td style="width: 96px;"><p class="publisher_font">{{$nowDate->format('Y年m月d日')}}</p></td>
					</tr>
					<tr style="text-align: right;">
						<td colspan="2"><p>八重洲無線株式会社</p></td>
					</tr>
					<tr style="text-align: right;">
						<td><p class="publisher_font">電話</p></td>
						<td><p class="publisher_font">03-6711-4xxx</p></td>
					</tr>
				</table>
			</div>
		</div>
		<div style="margin-top: 18px; margin-bottom: 16px;">
			<p class="greeting_font">平素は格別のご高配を賜り、厚く御礼申し上げます。下記の通りご請求申し上げます。</p>
		</div>
		<table style="width: 72%; border-collapse: collapse;" cellspacing="0">
			<tr>
				<td class="listvalue" style="width: 30.5%;">
					<p>ご請求対象期間</p>
				</td>
				<td class="listvalue" style="width: 69.5%; text-align: center;"><p>{{$billingMonthStart->format('Y年m月d日')}}～{{$billingMonthEnd->format('Y年m月d日')}}</p></td>
				<td class="height_adjust"><p style="height: 35px;" /></td>
			</tr>
			<tr>
				<td class="listvalue"><p>ご請求金額（税込）</p></td>
				<td class="listvalue" style="text-align: right;"><p>¥{{number_format($billing->billing_total_price + $billing->consumption_tax)}}</p></td>
				<td class="height_adjust"><p style="height: 35px;" /></td>
			</tr>
		</table>
		<table style="width: 100%; border-collapse: collapse; margin-top: 15pt;" cellspacing="0">
			<tr>
				<td class="listhead" style="width: 8%;">
					<p class="list_font">No.</p>
				</td>
				<td class="listhead" style="width: 14%;">
					<p class="list_font">契約開始日</p>
				</td>
				<td class="listhead" style="width: 10%;">
					<p class="list_font">商品番号</p>
				</td>
				<td class="listhead" style="width: 31%;">
					<p class="list_font">商品名</p>
				</td>
				<td class="listhead" style="width: 9%;">
					<p class="list_font">数量</p>
				</td>
				<td class="listhead" style="width: 14%;">
					<p class="list_font">単価</p>
				</td>
				<td class="listhead" style="width: 14%;">
					<p class="list_font">金額</p>
				</td>
				<td class="height_adjust"><p style="height: 32px;" /></td>
			</tr>
			@php $itemNumber = 1; @endphp
			@foreach($detailList as $item)
			<tr>
				<td class="listvalue">
					<p class="list_font">{{$itemNumber++}}</p>
				</td>
				<td class="listvalue">
					<p class="list_font">{{$item->contract_date->format('Y/m/d')}}</p>
				</td>
				<td class="listvalue">
					<p class="list_font">{{$item->plan_num}}</p>
				</td>
				<td class="listvalue">
					<p class="list_font">{{$item->plan_name}}</p>
				</td>
				<td class="listvalue" style="text-align: center;">
					<p class="list_font">{{number_format($item->amount)}}</p>
				</td>
				<td class="listvalue" style="text-align: right;">
				@if ($item->unit_price < 0)
					<p class="list_minus_font">¥{{number_format($item->unit_price)}}</p>
				@else
					<p class="list_font">¥{{number_format($item->unit_price)}}</p>
				@endif
				</td>
				<td class="listvalue" style="text-align: right;">
					@if ($item->total_amount < 0)
						<p class="list_minus_font">¥{{number_format($item->total_price)}}</p>
					@else
						<p class="list_font">¥{{number_format($item->total_price)}}</p>
					@endif
				</td>
				<td class="height_adjust"><p style="height: 38px;" /></td>
			</tr>
			@endforeach
			@for ($restNumber = $itemNumber; $restNumber <= 6; $restNumber++)
			<tr>
				<td class="listvalue">
					<p class="list_font">&nbsp;</p>
				</td>
				<td class="listvalue">
					<p class="list_font">&nbsp;</p>
				</td>
				<td class="listvalue">
					<p class="list_font">&nbsp;</p>
				</td>
				<td class="listvalue">
					<p class="list_font">&nbsp;</p>
				</td>
				<td class="listvalue">
					<p class="list_font">&nbsp;</p>
				</td>
				<td class="listvalue">
					<p class="list_font">&nbsp;</p>
				</td>
				<td class="listvalue">
					<p class="list_font">&nbsp;</p>
				</td>
				<td class="height_adjust"><p style="height: 38px;" /></td>
			</tr>
			@endfor
			<tr>
				<td colspan="5" rowspan="10" style="vertical-align: top; padding: 0pt;">
					<div class="remarks_frame">
						<p class="remarks">
							[備考]<br />
							お振替日が、祝日その他による銀行休日の場合、後営業日をお振替日とします。<br />
							契約詳細は IP無線サービスWebページにログインしマイページをご確認ください。
						</p>
					</div>
				</td>
				<td class="listvalue">
					<p class="list_font">小計</p>
				</td>
				<td class="listvalue" style="text-align: right;">
					<p class="list_font">¥{{number_format($billing->billing_total_price)}}</p>
				</td>
				<td class="height_adjust"><p style="height: 24px;" /></td>
			</tr>
			<tr>
				<td class="listvalue">
					<p class="list_font">消費税</p>
				</td>
				<td class="listvalue" style="text-align: right;">
					<p class="list_font">¥{{number_format($billing->consumption_tax)}}</p>
				</td>
				<td class="height_adjust"><p style="height: 24px;" /></td>
			</tr>
			<tr>
				<td class="listvalue">
					<p class="list_font">合計</p>
				</td>
				<td class="listvalue" style="text-align: right;">
					<p class="list_font">¥{{number_format($billing->billing_total_price + $billing->consumption_tax)}}</p>
				</td>
				<td class="height_adjust"><p style="height: 24px;" /></td>
			</tr>
			<tr>
				<td colspan="2" />
				<td class="height_adjust"><p style="height: 24px;" /></td>
			</tr>
			<tr>
				<td class="bank"><p class="list_font">振替日</p></td>
				<td class="bank"><p class="list_font">{{$transferDate->format('Y年m月d日')}}</p></td>
				<td class="height_adjust"><p style="height: 24px;" /></td>
			</tr>
			<tr>
				<td class="bank"><p class="list_font">お支払方法</p></td>
				<td class="bank"><p class="list_font">{{$billing->user->payment_type}}</p></td>
				<td class="height_adjust"><p style="height: 24px;" /></td>
			</tr>
			<tr>
				<td class="bank"><p class="list_font">金融機関名</p></td>
				<td class="bank"><p class="list_font">{{$billing->user->bank_name}}</p></td>
				<td class="height_adjust"><p style="height: 24px;" /></td>
			</tr>
			<tr>
				<td class="bank"><p class="list_font">支店名</p></td>
				<td class="bank"><p class="list_font">{{$billing->user->branchi_name}}</p></td>
				<td class="height_adjust"><p style="height: 24px;" /></td>
			</tr>
			<tr>
				<td class="bank"><p class="list_font">口座番号</p></td>
				<td class="bank"><p class="list_font">{{$billing->user->deposit_type}}&nbsp;{{$billing->user->account_num}}</p></td>
				<td class="height_adjust"><p style="height: 24px;" /></td>
			</tr>
			<tr>
				<td class="bank"><p class="list_font">口座名義</p></td>
				<td class="bank"><p class="list_font">{{$billing->user->account_name}}</p></td>
				<td class="height_adjust"><p style="height: 24px;" /></td>
			</tr>
		</table>
	</div>
@stop
