@extends('shop.body')

@section('container')
    <div class="">

        <h2>{{__("Line Details")}}</h3>

        @if(!empty($user->contractor_id))
        <div class="user-info">
          <div class=""><span>{{__("message.customer_code")}}: </span><span>{{$user->contractor_id}}</span></div>
          <div class=""><span>{{__("message.customer_name")}}: </span><span>{{$user->contract_name}}</span></div>
        </div>
        @endif

        <div class="list-talk-group p-4">
            <table class="table detail list-talk-group detail">
                <tbody>
                    <tr>
                        <td>{{__("Row Num")}}</td>
                        <td>{{$line->id}} </td>
                    </tr>
                    <tr>
                    <!--
                    <tr>
                        <td>{{__("Shop ID")}}</td>
                        <td>{{$line->shop_id}}</td>
                    </tr>
-->
                    <tr>
                        <td>{{__("VOIP Line Id")}}</td>
                            <td>{{$line->voip_line_id}}</td>
                        </tr>
                    <tr>
                        <td>{{__("Line Num")}}</td>
                        <td>{{$line->line_num}}</td>
                    </tr>
                    <tr>
                        <td>{{__("Name")}}</td>
                        <td>{{$line->voip_id_name}}</td>
                    </tr>
                    <tr>
                        <td>{{__("Call Type")}}</td>
                        <td>{{__($line->call_type)}}</td>
                    </tr>
                    <tr>
                        <td>{{__("Model ID")}}</td>
                        <td>{{$line->models_id}}</td>
                    </tr>
                    <tr>
                        <td>{{__("Sim ID")}}</td>
                        <td>{{$line->sim_id}}</td>
                    </tr>
                    <tr>
                        <td>{{__("Priority")}}</td>
                        <td>{{__($line->priority)}}</td>
                    </tr>
                    <tr>
                        <td>{{__("Individual")}}</td>
                        <td>{{__($line->individual)}}</td>
                    </tr>
                    <tr>
                        <td>{{__("Recording")}}</td>
                        <td>{{__($line->recording)}}</td>
                    </tr>
                    <tr>
                        <td>{{__("GPS")}}</td>
                        <td>{{__($line->gps)}}</td>
                    </tr>
                    <tr>
                        <td>{{__("Commander")}}</td>
                        <td>{{__($line->commander)}}</td>
                    </tr>
                    <tr>
                        <td>{{__("Individual Priority")}}</td>
                        <td>{{__($line->individual_priority)}}</td>
                    </tr>
                    <tr>
                        <td>{{__("Cue Reception")}}</td>
                        <td>{{__($line->cue_reception)}}</td>
                    </tr>
                    <tr>
                        <td>{{__("Video")}}</td>
                        <td>{{__($line->video)}}</td>
                    </tr>
                    <tr>
                        <td>{{__("Start Date")}}</td>
                        <td>{{$line->start_date}}</td>
                    </tr>
                    <tr>
                        <td>{{__("Status")}}</td>
                        <td>{{__($line->status)}}</td>
                    </tr>
                    <tr>
                        <td>{{__("Change application date")}}</td>
                        <td>{{$line->change_application_date}}</td>
                    </tr>
                    <tr>
                        <td>{{__("Contract Renewal Date")}}</td>
                        <td>{{$line->contract_renewal_date}}</td>
                    </tr>
                    <tr>
                        <td>{{__("Note")}}</td>
                        <td>
                            <span class="longtext">{!! nl2br(htmlspecialchars($line->memo)) !!}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <a class="btn" href="javascript:history.back()">{{__("Go Back")}}</a>
    </div>
@stop
