@extends('shop.export.master')

@section('content')
    <div>
        <div style="display:flex; align-items:center">
            <div class="float-left" style="width:50%;font-size: 14px; text-align:left; float:left">{{__("IP communication device line usage application")}}</div>
            <div class="text-right" style="width:50%; text-align:right; float:right; padding-right:20px;margin-bottom:-10px">{{__("IP line application form A01")}}</div>
        </div>
        <hr style="clear: both;margin-top:0px!important">
        <div  style="text-align:right; margin-bottom:8px">{{__("Date of issue")}}: {{$issuedDate}}</div>
        <div class="mt-3" style="display:flex; justify-content:space-between">
            <div style=" float:left">
                <div><span>{{__("Contractor")}}:</span> <span>{{$userRequest->user->contractor_id}}</span></div>
                <div>{{$userRequest->user->billing_post_number}}</div>
                <div>{{$userRequest->user->billing_municipalities}}, {{$userRequest->user->billing_prefectures}}</div>
                <div>{{$userRequest->user->representative_name}}</div>
                <div>{{$userRequest->user->billing_department}}</div>
                <div>{{$userRequest->user->contract_name}}</div>
            </div>
            <div style="margin-left: 20px; float:left">
                <div><span>{{__("Application Date")}}:</span><span>{{$userRequest->request_date}}</span></div>
                <div>
                    <table class="table-borderless">
                        <tbody>
                            <tr>
                                <td>{{__("Application Type")}}:</td>
                                <td style="width: 200px">
                                    <input type="checkbox" {{$userRequest->getRawValue('add_flg') ? 'checked' : ''}}> {{__("Add line")}}<br>
                                    <input type="checkbox" {{$userRequest->getRawValue('modify_flg') ? 'checked' : ''}}> {{__("Change line settings")}}<br>
                                    <input type="checkbox" {{$userRequest->getRawValue('pause_restart_flg') ? 'checked' : ''}}> {{__("Line pause / resume")}}<br>
                                    <input type="checkbox" {{$userRequest->getRawValue('discontinued_flg') ? 'checked' : ''}}> {{__("Line discontinuation")}}<br>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div style="float:right">
                <div><span>{{__("Customer Number")}}:</span><span>{{$userRequest->user->contractor_id}}</span></div>
                <div><span>{{__("Number of contracted lines")}}:</span><span>{{$userRequest->line_requests->count()}}</span></div>
                <div><span>{{__("Dealer code")}}:</span><span>{{$userRequest->shop->code}}</span></div>
                <div><span>{{__("Dealer name")}}:</span><span>{{$userRequest->shop->name}}</span></div>
            </div>
        </div>
        <div style="clear: both;"></div>
        <div class="mt-3" style="display:flex; justify-content:space-between">
            <div style=" float:left ; min-width:800px">
               <div style="font-size: 8px"><b>{{__("Change fee and monthly usage fee")}}</b></div>
               <hr>
               <div>
                <table class="mt-3 table-borderless" style="font-size: 8px; min-width:300px">
                       <thead>
                           <tr>
                               <td colspan="4">{{__('Scheduled to be billed from the next month onwards')}}</td>
                               <td colspan="4"></td>
                               <td colspan="2">{{__('Number of lines')}}</td>
                               <td colspan="2">{{__('Price')}}</td>
                           </tr>
                       </thead>
                       <tbody>
                           <tr>
                               <td rowspan="8" colspan="4">{{$nextMonth}}</td>
                               <td colspan="4">{{__("Line usage fee")}}</td>
                               <td colspan="2">{{intVal(round($fees['nextMonthFees']['line_usage_number']))}}</td>
                               <td colspan="2">¥{{intVal(round($fees['nextMonthFees']['line_usage_fee']))}}</td>
                           </tr>
                           <tr>
                               <td colspan="4">{{__("Line usage fee (daily rate)")}}</td>
                               <td colspan="2">{{$fees['nextMonthFees']['daily_line_usage_number']}}</td>
                               <td colspan="3">¥{{intVal(round($fees['nextMonthFees']['daily_line_usage_fee']))}}</td>
                           </tr>
                           <tr>
                               <td colspan="4">{{__("Invoice mailing service fee")}}</td>
                               <td colspan="2"></td>
                               <td colspan="2">¥{{intVal(round($fees['nextMonthFees']['invoice_mailing_service_fee']))}}</td>
                           </tr>
                           <tr>
                               <td colspan="4">{{__("Change fee")}}</td>
                               <td colspan="2">{{$fees['nextMonthFees']['change_line_number']}}</td>
                               <td colspan="2">¥{{intVal(round($fees['nextMonthFees']['change_fee']))}}</td>
                           </tr>
                           <tr>
                               <td colspan="4">{{__("Campaign discount")}}</td>
                               <td colspan="2">{{$fees['currentMonthFees']['line_campaign_number']}}</td>
                               <td colspan="2">¥-{{intVal(round($fees['nextMonthFees']['campaign_discount']))}}</td>
                           </tr>
                           <tr>
                               <td colspan="4">{{__("Expected billing amount")}}</td>
                               <td colspan="2"></td>
                               <td colspan="2">¥{{intVal(round($fees['nextMonthFees']['expected_billing_amount']))}}</td>
                           </tr>
                           <tr>
                               <td colspan="4">{{__("Consumption tax")}}</td>
                               <td colspan="2"></td>
                               <td colspan="2">¥{{intVal(round($fees['nextMonthFees']['consumption_tax']))}}</td>
                           </tr>
                           <tr>
                               <td colspan="4">{{__("Scheduled withdrawal amount")}}</td>
                               <td colspan="2"></td>
                               <td colspan="2">¥{{$fees['nextMonthFees']['scheduled_withdrawal_amount']}}</td>
                           </tr>
                       </tbody>
                   </table>
                   <table class="table-borderless" style="font-size: 8px; min-width:300px">
                       <thead>
                            <tr>
                                <td colspan="4" style="min-width: 95px!important;">{{__('Scheduled to be billed this month')}}</td>
                                <td colspan="4"></td>
                                <td colspan="2">{{__('Number of lines')}}</td>
                                <td colspan="2">{{__('Price')}}</td>
                            </tr>
                       </thead>
                       <tbody>
                            <tr>
                                <td rowspan="8" colspan="4">{{$currentMonth}}</td>
                                <td colspan="4">{{__("Line usage fee")}}</td>
                                <td colspan="2">{{intVal(round($fees['currentMonthFees']['line_usage_number']))}}</td>
                                <td colspan="2">¥{{intVal(round($fees['currentMonthFees']['line_usage_fee']))}}</td>
                            </tr>
                            <tr>
                                <td colspan="4">{{__("Line usage fee (daily rate)")}}</td>
                                <td colspan="2">{{intVal(round($fees['currentMonthFees']['daily_line_usage_number']))}}</td>
                                <td colspan="2">¥{{intVal(round($fees['currentMonthFees']['daily_line_usage_fee']))}}</td>
                            </tr>
                            <tr>
                                <td colspan="4">{{__("Invoice mailing service fee")}}</td>
                                <td colspan="2"></td>
                                <td colspan="2">¥{{intVal(round($fees['currentMonthFees']['invoice_mailing_service_fee']))}}</td>
                            </tr>
                            <tr>
                                <td colspan="4">{{__("Change fee")}}</td>
                                <td colspan="2">{{intVal(round($fees['currentMonthFees']['change_line_number']))}}</td>
                                <td colspan="2">¥{{intVal(round($fees['currentMonthFees']['change_fee']))}}</td>
                            </tr>
                            <tr>
                                <td colspan="4">{{__("Campaign discount")}}</td>
                                <td colspan="2">{{$fees['nextMonthFees']['line_campaign_number']}}</td>
                                <td colspan="2">¥-{{intVal(round($fees['currentMonthFees']['campaign_discount']))}}</td>
                            </tr>
                            <tr>
                                <td colspan="4">{{__("Expected billing amount")}}</td>
                                <td colspan="2"></td>
                                <td colspan="2">¥{{intVal(round($fees['currentMonthFees']['expected_billing_amount']))}}</td>
                            </tr>
                            <tr>
                                <td colspan="4">{{__("Consumption tax")}}</td>
                                <td colspan="2"></td>
                                <td colspan="2">¥{{intVal(round($fees['currentMonthFees']['consumption_tax']))}}</td>
                            </tr>
                            <tr>
                                <td colspan="4">{{__("Scheduled withdrawal amount")}}</td>
                                <td colspan="2"></td>
                                <td colspan="2">¥{{intVal(round($fees['currentMonthFees']['scheduled_withdrawal_amount']))}}</td>
                            </tr>
                       </tbody>
                   </table>
               </div>
            </div>
            <div style=" float:right;width: 40%">
                <div style="font-size: 8px">
                    <div style="font-size: 8px"><b>{{__("Confirmation of application details Signature line")}}</b></div>
                    <div style="font-size: 8px">
                        {{__("Apply for the IP wireless line contract with the following contents.")}}
                    </div>
                    <div style="height: 30px"></div>
                    <div style="font-size: 8px"><b>{{$contract_name->contract_name}}様</b></div>
                    <div style="font-size: 8px"><b>{{__("Signature")}}</b></div>
                </div>
                <div>
                    <input type="checkbox">{{__("Confirmed application details by phone / email")}}
                </div>
                <div>
                    {{$userRequest->precautionary_statement}}
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>
        <div style="clear: both;"></div>
        <div class="margin-top-heading" style="margin-top:7px; margin-bottom:7px"><b>{{__("Contract line and application details")}}</b></div>
        @if ( $userRequest->line_requests->count() > 0)
            <table style="font-size: 8px">
                <thead>
                    <tr>
                        <th scope="col">{{__("Line ID")}}</th>
                        <th scope="col">{{__("Line number")}}</th>
                        <th scope="col">{{__("Voip Id Name")}}</th>
                        <th scope="col">{{__("Start date of use")}}</th>
                        <th scope="col">{{__("Change application date")}}</th>
                        <th scope="col">{{__("Status")}}</th>
                        <th scope="col">{{__("Application type")}}</th>
                        <th scope="col">{{__("Usage")}}</th>
                        <th scope="col">{{__("Plan name")}}</th>
                        <th scope="col">{{__("Monthly usage fee")}}</th>
                        <th scope="col">{{__("Contract renewal date")}}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($userRequest->line_requests as $key => $line)
                    <tr>
                        <td>{{$line->voip_line_id}}</td>
                        <td>{{$line->line_num}}</td>
                        <td>{{$line->voip_id_name}}</td>
                        <td>{{$line->start_date}}</td>
                        <td>{{$line->change_application_date}}</td>
                        <td>{{__($line->status)}}</td>
                        <td>{{__($line->request_type)}}</td>
                        <td>{{$line->user_line_plan_request->plan->plan_num}}</td>
                        <td>{{$line->user_line_plan_request->plan->plan_name}}</td>
                        <td>{{$line->user_line_plan_request->plan->usage_unit_price}}</td>
                        <td>{{$line->contract_renewal_date}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="mt-3">{{__("No line requested.")}}</div>
        @endif
    </div>
@stop
