@extends('operator.body')

@section('container')
<div>
    <div class="mt-3">
        <h2>{{__("Billing List")}}</h2>
    </div>

    <form method="get" action="{{route('operator.billingList')}}" class="framed">
        <div class="form-group row">
            <label class="col-auto col-form-label">{{__("Billing Year")}}</label>
            <div class="col-md-2">
                <input class="form-control" name="billing_y" type="text" maxlength="4" value="{{$billing_y}}">
            </div>
            <label class="col-auto col-form-label">{{__("Billing Month")}}</label>
            <div class="col-md-2">
                <input class="form-control" name="billing_m" type="text" maxlength="2" value="{{$billing_m}}">
            </div>
            <div class="col-auto">
                <input type="submit" class="btn btn-primary" value="{{__("Search")}}">
            </div>
        </div>
    </form>

    @if (count($billingList) > 0)
    <form method="get" action="{{route('operator.billingCsvOutput')}}">
        @csrf
        <input type="hidden" name="billing_y" value="{{$billing_y}}">
        <input type="hidden" name="billing_m" value="{{$billing_m}}">
        <div class="mt-3 text-right">
            <input type="submit" class="btn btn-primary" value="{{__("Csv Output")}}">
            <a class="btn btn-update" href="" data-name="{{$billing_y . __('Year') . $billing_m . __('Month')}}" data-href="{{route('operator.statusUpdate', ['billing_y' => $billing_y, 'billing_m' => $billing_m])}}" data-btn-comfirm>{{__("Status Update")}}</a>            
        </div>
    </form>
    @endif
    @php $itemNumber = $billingList->firstItem(); @endphp
    <div class="list-billing p-4">
        <table class="table list-billing list list-m">
            <thead class='p-3'>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">{{__("Contract Name")}}</th>
                    <th scope="col">{{__("Dealer name")}}</th>
                    <th scope="col" class="text-center">{{__("Billing Total")}}</th>
                    <th scope="col" class="text-center">{{__("Incentive Total")}}</th>
                    <th scope="col" class="text-center">{{__("Status")}}</th>
                    <th scope="col" class="text-center">{{__("Action")}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($billingList as $item)
                    <tr>
                        <td>{{$itemNumber++}}</td>
                        <td>{{$item->contract_name}}</td>
                        <td>{{$item->shop_name}}</td>
                        <td class="text-right">{{number_format($item->billing_total_price)}}</td>
                        <td class="text-right">{{number_format($item->incentive_total_price)}}</td>
                        <td>{{$item->getStatusColumn()}}</td>
                        <td class="text-center">
                            <a href="{{route("operator.billingDetail", ['id' => $item->id])}}" class="btn">{{__("Detail")}}</a>
                        </td>
                    </tr>
                @endforeach
                {{ $billingList->links() }}
            </tbody>
        </table>
    </div>
</div>
@include('modals.modal-comfirm')
@stop
