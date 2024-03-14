@extends('admin.body')

@section('container')
    <div>
        <div class="row">
            <div class="col-md-10"><h3>{{$title}}</h3></div>
            <div class="col-md-2">
                <a class="btn btn-primary" href="{{route("{$prefixRouteName}Input")}}">{{__("Create")}}</a>
            </div>
        </div>
        <form method="get" action="{{route("{$prefixRouteName}List")}}">
            <div class="row mt-3">
                <div class="col-md-8">
                    <input class="form-control" name="s" value="">
                </div>
                <div class="col-md-4">
                    <input type="submit" class="btn btn-primary" value="{{__("Search")}}"/>
                </div>
            </div>
        </form>
        @if ($list->count() > 0)
            <table class="table table-borderless list" data-delete-url="{{route("{$prefixRouteName}HandleDelete")}}">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{__("Plan No")}}</th>
                        <th scope="col">{{__("Calculation unit")}}</th>
                        <th scope="col">{{__("Plan name")}}</th>
                        <th scope="col">{{__("Effective date")}}</th>
                        <th scope="col">{{__("Expire date")}}</th>
                        <th scope="col">{{__("Dealer Web")}}</th>
                        <th scope="col">{{__("Authority")}}</th>
                        <th scope="col">{{__("Calculation type")}}</th>
                        <th scope="col">{{__("Usage statement")}}</th>
                        <th scope="col">{{__("Incentive details")}}</th>
                        <th scope="col">{{__("Cancellation limit period")}}</th>
                        <!--<th scope="col">{{__("Usage fee unit price")}}</th>-->
                        <th scope="col">{{__("Period")}}</th>
                        <!--<th scope="col">{{__("Incentive unit price")}}</th>-->
                        <th scope="col">{{__("Action")}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $key => $item)
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td>{{$item->plan_num}}</td>
                            <td>{{$item->calculation_unit}}</td>
                            <td>{{$item->plan_name}}</td>
                            <td>{{$item->effective_date}}</td>
                            <td>{{$item->expire_date}}</td>
                            <td>{{$item->shop_web}}</td>
                            <td>{{$item->authority}}</td>
                            <td>{{$item->calculation_type}}</td>
                            <td>{{$item->usage_details_description}}</td>
                            <td>{{$item->incentive_description}}</td>
                            <td>{{$item->cancellation_limit_period}}</td>
                            <!--<td>{{$item->usage_unit_price}}</td>-->
                            <td>{{$item->period}}</td>
                            <!--<td>{{$item->incentive_unit_price}}</td>-->
                            <td>
                                <a class="btn" href="{{route("{$prefixRouteName}Detail", ['id' => $item->id])}}">{{__("View")}}</a>
                                <a class="btn btn-delete" href="" data-id="{{$item->id}}" data-name="{{$item->plan_num}}" data-btn-delete>{{__("Delete")}}</a>
                                <a class="btn btn-update" href="{{route("{$prefixRouteName}Edit", ['id' => $item->id])}}">{{__("Update")}}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $list->links() }}
        @else
            <div class="mt-3">{{__($none_result_message)}}</div>
        @endif
    </div>
    @include('modals.modal-delete')
@stop
