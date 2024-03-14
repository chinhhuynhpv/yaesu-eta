@extends('operator.body')

@section('container')
    <div id="app_detali_box" class="mt-3">

        <h2>{{__("Real List of lines and talk groups")}}</h3>

        @if(!empty($user->contractor_id))
        <div class="user-info">
          <div class=""><span>{{__("message.customer_code")}}: </span><span>{{$user->contractor_id}}</span></div>
          <div class=""><span>{{__("message.customer_name")}}: </span><span>{{$user->contract_name}}</span></div>
        </div>
        @endif

        <div class="nav-item rht">
            <a href="{{route('operator.exportCSVLineList', ['id'=>$userId])}}" class="nav-link btn btn-primary">{{__("Export CSV")}}</a>
        </div>
        @include('operator.line_list.include.talk_group_setting') 

        @include('operator.line_list.include.line_setting')

        @include('operator.line_list.include.line_talk_group')
        
        @if(!empty($userId))
            <a class="btn" href="{{route('admin.userList')}}">{{__("Go Back")}}</a>
        @else
        <a class="btn" href="{{route('admin.top')}}">{{__("Go Back")}}</a>
        @endif    
    </div>
@stop
