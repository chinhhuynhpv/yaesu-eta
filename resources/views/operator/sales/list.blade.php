@extends('operator.body')

@section('container')
<div>
    <div class="mt-3">
        <h2>{{__("Sales List")}}</h2>
    </div>

    <form method="get" action="{{route('operator.salesList')}}" class="framed">
        <div class="form-group row">
            <label class="col-auto col-form-label">{{__("Sales Year")}}</label>
            <div class="col-md-2">
                <input class="form-control" name="sales_y" type="text" maxlength="4" value="{{$sales_y}}">
            </div>
            <label class="col-auto col-form-label">{{__("Sales Month")}}</label>
            <div class="col-md-2">
                <input class="form-control" name="sales_m" type="text" maxlength="2" value="{{$sales_m}}">
            </div>
            <div class="col-auto">
                <input type="submit" class="btn btn-primary" value="{{__("Search")}}">
            </div>
        </div>
    </form>

    @if (count($salesList) > 0)
    <form method="get" action="{{route('operator.salesCsvOutput')}}">
        @csrf
        <input type="hidden" name="sales_y" value="{{$sales_y}}">
        <input type="hidden" name="sales_m" value="{{$sales_m}}">
        <div class="mt-3 text-right">
            <input class="" name="incentive_only" type="checkbox" value="1">
            <label class="col-form-label">{{__("Incentive Only")}}</label>
            <input type="submit" class="btn btn-primary" value="{{__("Csv Output")}}">
        </div>
    </form>
    @endif
    @php $itemNumber = $salesList->firstItem(); @endphp
    <div class="list-sales p-4">
        <table class="table list-sales list list-m">
            <thead class='p-3'>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">{{__("Contract Name")}}</th>
                    <th scope="col">{{__("Dealer name")}}</th>
                    <th scope="col" class="text-center">{{__("Sales Total")}}</th>
                    <th scope="col" class="text-center">{{__("Incentive Total")}}</th>
                    <th scope="col" class="text-center">{{__("Action")}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salesList as $item)
                    <tr>
                        <td>{{$itemNumber++}}</td>
                        <td>{{$item->contract_name}}</td>
                        <td>{{$item->shop_name}}</td>
                        <td class="text-right">{{number_format($item->sales_total_price)}}</td>
                        <td class="text-right">{{number_format($item->incentive_total_price)}}</td>
                        <td class="text-center">
                            <a href="{{route("operator.salesDetail", ['id' => $item->id])}}" class="btn">{{__("Detail")}}</a>
                        </td>
                    </tr>
                @endforeach
                {{ $salesList->links() }}
            </tbody>
        </table>
    </div>
</div>
@stop
