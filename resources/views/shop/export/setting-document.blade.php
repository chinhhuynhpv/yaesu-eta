@extends('shop.export.master')

@section('content')
    <div>
        <div style="display:flex">
            <h5 class="float-left" style="font-size: 14px; width:50%; float:left; margin-bottom:0px">{{__("IP communication device setting report")}}</h5>
            <div style="width: 50%;  float:right">{{__("All Line")}}</div>
        </div>
        <hr style="clear: both; margin-top:0px">
        <div style="text-align:right; margin-bottom:8px">{{__("Date of issue")}}: {{$issuedDate}}</div>
        <div class="mt-3" style="display:flex; justify-content:space-between">
            <div style=" float:left">
                <div>{{$userRequest->user->billing_manager_name}}</div>
                <div>{{$userRequest->user->billing_department}}</div>
                <div>
                    {{$userRequest->user->contract_name}}
                </div>
            </div>
            <div style=" float:right">
            <div><span class="border-black border-right-none border-bottom-none display-inline-block td-width-left">{{__("Customer Number")}}</span><span class="border-black display-inline-block border-bottom-none td-width-right">{{$userRequest->user->contractor_id}}</span></div>
                <div><span class="border-black border-right-none display-inline-block td-width-left">{{__("Number of contracted lines")}}</span><span class="border-black display-inline-block td-width-right">{{$userRequest->line_requests->count()}}</span></div>
            </div>
        </div>
        <div style="clear: both;"></div>
        <h5 class="margin-top-heading">{{__("Line ID settings")}}</h5>
        @include('shop.export.child-lists.line-id-list')
        <h5 class="margin-top-heading">{{__("Line ID-Group settings")}}</h5>
        @include('shop.export.child-lists.line-talk-group')
        <h5 class="margin-top-heading">{{__("Registered group")}}</h5>
        @include('shop.export.child-lists.talk-group-list')
        <br>
        <div style="display:flex; justify-content:space-between; margin-top:25px; padding-top:20px">
            <div style=" float:left">
                <div>{{__("Reference")}}</div>
                <div>{{$userRequest->remark}}</div>
            </div>
            <div style=" float:right">
                <div><span class="border-black border-right-none border-bottom-none display-inline-block pl-2" style="height: 24px; width: 50px">{{__("Dealer")}}</span><span class="border-black display-inline-block border-bottom-none td-width-right pl-2" style="height: 24px;">{{$userRequest->shop->code}}</span></div>
                <div><span class="border-left-black border-bottom-black display-inline-block pl-2" style="height: 24px; width: 50px"></span><span class="border-black display-inline-block td-width-right pl-2" style="height: 24px;">{{$userRequest->shop->name}}</span></div>
            </div>
        </div>
    </div>
@stop
