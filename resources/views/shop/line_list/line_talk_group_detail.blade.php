@extends('shop.body')

@section('container')
    <div class="">
    
        <h2>{{__("Detail Line Talk Group")}}</h2>
        
        @if(!empty($user->contractor_id))
            <div class="user-info">
            <div class=""><span>{{__("message.customer_code")}}: </span><span>{{$user->contractor_id}}</span></div>
            <div class=""><span>{{__("message.customer_name")}}: </span><span>{{$user->contract_name}}</span></div>
            </div>
        @endif

        <div class="list-talk-group p-4">
            <table class="table list-talk-group detail">

                <tbody>
                    <tr>
                        <td>{{__("Row Num")}}</td>
                        <td>{{$lineTalkGroup[0]->id}} </td>
                    </tr>
                    <tr>
                        <td>{{__("Shop Id")}}</td>
                        <td>{{$lineTalkGroup[0]->shop_id}} </td>
                    </tr>
                    <tr>
                        <td>{{__("Line Id")}}</td>
                        <td>{{$lineTalkGroup[0]->voip_line_id}} </td>
                    </tr>
                    <tr>
                        <td>{{__("Line Num")}}</td>
                        <td>{{$lineTalkGroup[0]->line_num}} </td>
                    </tr>
                    <tr>
                        <td>{{__("Voip Id Name")}}</td>
                        <td>{{$lineTalkGroup[0]->voip_id_name}} </td>
                    </tr>
                    <tr>
                    @foreach($lineTalkGroup as $item)
                        @if($item->number == 1)
                        <tr>
                            <td>{{__("Group Owner")}}</td>
                            <td>{{$item->name}}</td>
                        </tr>
                        @endif
                    @endforeach
                    @foreach($lineTalkGroup as $item)
                        @if($item->number != 1)
                            <tr>
                                <td>{{__("Group Select")}} {{$item->number - 1}}</td>
                                <td>{{$item->name}}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <a class="btn" href="javascript:history.back()">{{__("Go Back")}}</a>
    </div>
@stop
