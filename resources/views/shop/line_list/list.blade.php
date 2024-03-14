@extends('shop.body')

@section('container')
    <div id="app_detali_box" class="">
        <div class="p-4">
            <h2>{{__("List line")}}</h2>

            <div class="user-info-exist">
                <div class=""><span>{{__("message.customer_code")}}: </span><span>{{$user->contractor_id}}</span></div>
                <div class=""><span>{{__("message.customer_name")}} </span><span>{{$user->contract_name}}</span></div>
                <div class=""><span>{{__("Number of line")}}: </span><span>  @if(!empty($line)) {{count($lines)}} @else 0 @endif</span></div>
            </div>
        </div>
        
        @include('shop.line_list.include.talk_group_setting')
        
        @include('shop.line_list.include.line_setting')
        
        @include('shop.line_list.include.line_talk_group')

        <a class="btn" href="javascript:history.back()">{{__("Go Back")}}</a>
    </div>
@stop
