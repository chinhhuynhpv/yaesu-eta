@extends('operator.body')

@section('container')
<div>
    <div class="mt-3">
        <h2>{{__("Sale Details")}}</h2>
    </div>
    
    <table class="table mt-3" style="width: auto;">
        <tbody>
            <tr>
                <td>{{__("Contract Name")}}</td>
                <td>{{$sale->user->contract_name}}</td>
            </tr>
            <tr>
                <td>{{__("Dealer name")}}</td>
                <td>{{$sale->shop->name}}</td>
            </tr>
            <tr>
                <td>{{__("Sales Total")}}</td>
                <td class="text-right">{{number_format($sale->sales_total_price)}}</td>
            </tr>
            <tr>
                <td>{{__("Incentive Total")}}</td>
                <td class="text-right">{{number_format($sale->incentive_total_price)}}</td>
            </tr>
        </tbody>
    </table>
    @php $itemNumber = 1; @endphp
    <div class="list-billing-detail p-4">
        <table class="table list-billing-detail list list-m">
            <thead class='p-3'>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">{{__("Contract Date")}}</th>
                    <th scope="col">{{__("Plan No")}}</th>
                    <th scope="col">{{__("Plan Name")}}</th>
                    <th scope="col" class="text-center">{{__("Amount")}}</th>
                    <th scope="col" class="text-center">{{__("Unit Price")}}</th>
                    <th scope="col" class="text-center">{{__("Total")}}</th>
                    <th scope="col" class="text-center">{{__("Incentive Unit")}}</th>
                    <th scope="col" class="text-center">{{__("Incentive Total")}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detailList as $item)
                    <tr>
                        <td>{{$itemNumber++}}</td>
                        <td>{{$item->contract_date->format('Y/m/d')}}</td>
                        <td>{{$item->plan_num}}</td>
                        <td>{{$item->plan_name}}</td>
                        <td class="text-right">{{number_format($item->amount)}}</td>
                        <td class="text-right">{{number_format($item->unit_price)}}</td>
                        <td class="text-right">{{number_format($item->total_price)}}</td>
                        <td class="text-right">{{number_format($item->incentive_unit_price)}}</td>
                        <td class="text-right">{{number_format($item->incentive_total_price)}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    <div class="lft">
        <a class="btn" href="#" onclick="history.back();">{{__("Back")}}</a>
    </div>

    </div>
</div>
@stop
