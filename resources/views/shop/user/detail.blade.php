@extends('shop.body')

@section('container')
    <div class="content-talk-group p-4">

        <h2 class="text-center">{{__("Detail Talk Group")}}</h2>

        @if(!empty($user->contractor_id))
        <div class="user-info">
          <div class=""><span>{{__("message.customer_code")}}: </span><span>{{$user->contractor_id}}</span></div>
          <div class=""><span>{{__("message.customer_name")}}: </span><span>{{$user->contract_name}}</span></div>
        </div>
        @endif

        <div class="mt-5">
            @if(!empty($talkGroup))
                <table class="table detail">
                    <tbody>
                        <tr>
                            <td>{{__("Row Num")}}</td>
                            <td>{{$talkGroup->id}} </td>
                        </tr>
                        <tr>
                        <tr>
                            <td>{{__("Request Type")}}</td>
                            <td>{{$talkGroup->request_type}}</td>
                        </tr>
                        <tr>
                            <td>{{__("VOIP Group ID")}}</td>
                            <td>{{$talkGroup->voip_group_id}}</td>
                        </tr>
                        <tr>
                            <td>{{__("Group Name")}}</td>
                            <td>{{$talkGroup->name}}</td>
                        </tr>
                        <tr>
                            <td>{{__("Priority")}}</td>
                            <td>{{__($talkGroup->priority)}}</td>
                        </tr>
                        <tr>
                            <td>{{__("Member View")}}</td>
                            <td>{{__($talkGroup->member_view)}}</td>
                        </tr>
                        <tr>
                            <td>{{__("Group Manager")}}</td>
                            <td>{{$talkGroup->group_responsible_person}}</td>
                        </tr>
                        <tr>
                            <td>{{__("Status")}}</td>
                            <td>{{__($talkGroup->status)}}</td>
                        </tr>
                        <tr>
                            <td>{{__("Date Of Creation")}}</td>
                            <td>{{$talkGroup->created_at}}</td>
                        </tr>
                        <tr>
                            <td>{{__("Update Date")}}</td>
                            <td>{{$talkGroup->updated_at}}</td>
                        </tr>
                    </tbody>
                </table>
            @else
                <div class="p-5">
                    {{__("Not Found")}}
                </div>
            @endif
        </div>
        <div class="lft">
            <input type="button" class="btn" value="{{__("Fix")}}" onclick="history.back()">
        </div>
    </div>
@stop
