@extends('operator.body')

@section('container')
    <div class="">
        <div class="">
            <h2>
            @if(empty($line))
                {{__("Add Line List")}}
            @else
                {{__("Edit Line List")}}
            @endif
            </h2>
        </div>

        @if(!empty($user->contractor_id))
        <div class="user-info">
          <div class=""><span>{{__("message.customer_code")}}: </span><span>{{$user->contractor_id}}</span></div>
          <div class=""><span>{{__("message.customer_name")}}: </span><span>{{$user->contract_name}}</span></div>
        </div>
        @endif
<!--
        <div class=""><a class="btn" href="{{route('admin.lineList', ['id'=>$userId])}}">{{__("Back to list")}}</a></div>
-->

        <form method="post" action="{{route('operator.lineStore')}}" class="iu">
            @csrf
            <input type="hidden" name="id" class="form-control" value="{{$line->id}}" required>
            <input type="hidden" name="shopId" value='{{$line->shop_id}}'>
            <input type="hidden" name="currentUserId" value='{{$userId}}'>
            <div class="form-group">
                <label>{{__("Line Id")}}</label>
                <input type="text" name="voipLineId" @error('voipLineId') is-invalid @enderror class="form-control" value="@if(old('voipLineId')){{old('voipLineId')}}@else{{$line->voip_line_id}}@endif" required>
                @error('voipLineId')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <input type="hidden" name="userId" class="form-control" value="{{$line->user_id}}" required>
            </div>
            <div class="form-group">
                <label>{{__("Line number")}}</label>
                <input type="text" name="lineNum" @error('lineNum') is-invalid @enderror readonly class="form-control" value="@if(old('lineNum')){{old('lineNum')}}@else{{$line->line_num}}@endif" required>
                @error('lineNum')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>{{__("Voip Id Name")}}</label>
                <input type="text" name="voipIdName" class="form-control" value="@if(old('voipIdName')){{old('voipIdName')}}@else{{$line->voip_id_name}}@endif" required>
            </div>
            <div class="form-group">
                <label>{{__("Model ID")}}</label>
                <select class="form-select form-select-lg" name="modelId">
                    @foreach($models as $m)
                        <option value="{{$m->id}}"  @if($m->id == $line->models_id) selected @endif> {{$m->product_name}} </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>{{__("SIM Number")}}</label>
                <input type="text" name="simNum" class="form-control" value="@if(old('simNum')){{old('simNum')}}@else{{$line->sim_num}}@endif">
            </div>
            <div class="form-group">
                <label>{{__("Usage Plan")}}</label>
                <select class="form-select form-select-lg" name="usagePlan">
                    @foreach($usagePlans as $usagePlan)
                        <option value="{{$usagePlan->id}}"  @if(!empty($tLinePlan) && $usagePlan->id == $tLinePlan->plan_id) selected @endif>{{$usagePlan->plan_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>{{__("Start Date")}}</label>
                <input type="date" name="startDateOfPlan" @error('startDateOfPlan') is-invalid @enderror  value="@if(!empty($tLinePlan->start_date)){{$tLinePlan->start_date}}@else{{old('startDateOfPlan')}}@endif">
                @error('startDateOfPlan')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>{{__("Automatic Update")}}</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" value="1" name="automaticUpdate" @if((!empty($tLinePlan) && $tLinePlan->automatic_update == 'no') || (old('automaticUpdate') == '1')) checked @endif>
                    <label class="form-check-label" for="flexRadioDefault1">
                    {{__("No")}}
                    </label>
                    </div>
                    <div class="form-check">
                    <input class="form-check-input" type="radio" value="2" name="automaticUpdate" @if((!empty($tLinePlan) && $tLinePlan->automatic_update == 'yes') || (old('automaticUpdate') == '2')) checked @endif>
                    <label class="form-check-label" for="flexRadioDefault2">
                    {{__("Yes")}}
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label>{{__("Option plan")}}</label>
            </div>
            <table class="table-combo table table-striped" id="table-combo-retail">
                <thead>
                    <tr class="col">
                        <th scope="col">{{__("Select")}}</th>
                        <th scope="col">{{__("Type")}}</th>
                        <th scope="col" style="text-align:center">{{__("Option Name")}}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($optionPlans as $optionPlan)
                    <tr class="col">
                        <td>
                            <label class="custome-checkbox">
                            <input class="optionPlan" type="checkbox" value="{{$optionPlan->id}}" name="optionPlan[]" 
                            @if(!empty($tLinePlan) && (($optionPlan->id == $tLinePlan->option_id1)
                                || ( $optionPlan->id == $tLinePlan->option_id2)
                                || ( $optionPlan->id == $tLinePlan->option_id3)
                                || ( $optionPlan->id == $tLinePlan->option_id4)
                                || ( $optionPlan->id == $tLinePlan->option_id5)
                                || ( $optionPlan->id == $tLinePlan->option_id6)
                                || ( $optionPlan->id == $tLinePlan->option_id7)
                                || ( $optionPlan->id == $tLinePlan->option_id8)
                                || ( $optionPlan->id == $tLinePlan->option_id9)
                                || ( $optionPlan->id == $tLinePlan->option_id10))
                            ) checked @endif> 
                            <span></span>
                            </label>
                        </td>
                        <td class="lbl-strong">
                            {{$optionPlan->option_type}}
                        </td>
                        <td class="lbl-strong">
                            {{$optionPlan->plan_name}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="form-group">
                <label>{{__("Voip Line password")}}</label>
                <input type="text" name="voipLinePassword" @error('voipLinePassword') is-invalid @enderror class="form-control" value="@if(old('voipLinePassword')){{old('voipLinePassword')}}@else{{$line->voip_line_password}}@endif">
                @error('voipLinePassword')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>{{__("Call method")}}</label>
                <div class="radio-box">
                    <input type="radio" name="callType" value="1" {{ $line->call_type == __('Simple') ? 'checked' : ''}}> {{__("Simple")}}
                </div>
                <div class="radio-box">
                    <input type="radio" name="callType" value="2"  {{$line->call_type == __('Full duflex') ? 'checked' : ''}}> {{__("Full duflex")}}
                </div>
            </div>
            <div class="form-group">
                <label>{{__("priority")}}</label>
                <select class="form-select form-select-lg" name="priority" required>
                    <option value="" @if($line->priority == '') selected @endif> {{__("Choose one")}} </option>
                    <option value="1"  @if($line->priority == __('Level 1')) selected @endif> {{__("Level 1")}}</option>
                    <option value="2"  @if( $line->priority == __('Level 2')) selected @endif> {{__("Level 2")}} </option>
                    <option value="3"  @if( $line->priority == __('Level 3')) selected @endif> {{__("Level 3")}} </option>
                    <option value="4"  @if( $line->priority == __('Level 4')) selected @endif> {{__("Level 4")}} </option>
                    <option value="5"  @if( $line->priority == __('Level 5')) selected @endif> {{__("Level 5")}} </option>
                    <option value="6"  @if( $line->priority == __('Level 6')) selected @endif> {{__("Level 6")}} </option>
                    <option value="7"  @if( $line->priority == __('Level 7')) selected @endif> {{__("Level 7")}} </option>
                    <option value="8"  @if( $line->priority == __('Level 8')) selected @endif> {{__("Level 8")}} </option>
                    <option value="9"  @if( $line->priority == __('Level 9')) selected @endif> {{__("Level 9")}} </option>
                    <option value="10" @if( $line->priority == __('Level 10')) selected @endif> {{__("Level 10")}} </option>
                </select>
            </div>
            <div class="form-group">
                <label>{{__("Individual communication")}}</label>
                <div class="row">
                    <div class="radio-box">
                        <input type="radio" name="individual" value="2"  {{$line->individual == __('off') ? 'checked' : ''}}> {{__("Off")}}
                    </div>
                    <div class="radio-box">
                        <input type="radio" name="individual" value="1" {{ $line->individual == __('on') ? 'checked' : ''}}> {{__("On")}}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>{{__("Recording")}}</label>
                <div class="row">
                    <div class="radio-box">
                        <input type="radio" name="recording" value="2"  {{$line->recording == __('off') ? 'checked' : ''}}> {{__("Off")}}
                    </div>
                    <div class="radio-box">
                        <input type="radio" name="recording" value="1" {{ $line->recording == __('on') ? 'checked' : ''}}> {{__("On")}}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>{{__("GPS")}}</label>
                <div class="row">
                    <div class="radio-box">
                        <input type="radio" name="gps" value="2"  {{$line->gps == __('off') ? 'checked' : ''}}> {{__("Off")}}
                    </div>
                    <div class="radio-box">
                        <input type="radio" name="gps" value="1" {{ $line->gps == __('on') ? 'checked' : ''}}> {{__("On")}}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>{{__("Commander")}}</label>
                <div class="row">
                    <div class="radio-box">
                        <input type="radio" name="commander" value="2"  {{$line->commander == __('off') ? 'checked' : ''}}> {{__("Off")}}
                    </div>
                    <div class="radio-box">
                        <input type="radio" name="commander" value="1" {{ $line->commander == __('on') ? 'checked' : ''}}> {{__("On")}}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>{{__("Individual priority")}}</label>
                <div class="row">
                    <div class="radio-box">
                        <input type="radio" name="individualPriority" value="2"  {{$line->individual_priority == __('off') ? 'checked' : ''}}> {{__("Off")}}
                    </div>
                    <div class="radio-box">
                        <input type="radio" name="individualPriority" value="1" {{ $line->individual_priority == __('on') ? 'checked' : ''}}> {{__("On")}}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>{{__("CUE reception")}}</label>
                <div class="row">
                    <div class="radio-box">
                        <input type="radio" name="cueReception" value="2"  {{$line->cue_reception == __('off') ? 'checked' : ''}}> {{__("Off")}}
                    </div>
                    <div class="radio-box">
                        <input type="radio" name="cueReception" value="1" {{ $line->cue_reception == __('on') ? 'checked' : ''}}> {{__("On")}}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>{{__("Video")}}</label>
                <div class="row">
                    <div class="radio-box">
                        <input type="radio" name="video" value="2"  {{$line->video == __('off') ? 'checked' : ''}}> {{__("Off")}}
                    </div>
                    <div class="radio-box">
                        <input type="radio" name="video" value="1" {{ $line->video == __('on') ? 'checked' : ''}}> {{__("On")}}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>{{__("Start date of use")}}</label>
                <input type="date" name="startDate" @error('startDate') is-invalid @enderror class="form-control" value="{{$line->start_date}}">
                @error('startDate')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>{{__("Status")}}</label>
                <div class="row">
                    <div class="radio-box">
                        <input type="radio" name="status" value="1" {{ $line->status == __('in use') ? 'checked' : ''}}> {{__("In use")}}
                    </div>
                    <div class="radio-box">
                        <input type="radio" name="status" value="2"  {{$line->status == __('in active') ? 'checked' : ''}}> {{__("In active")}}
                    </div>
                    <div class="radio-box">
                        <input type="radio" name="status" value="3"  {{$line->status == __('discontinued') ? 'checked' : ''}}> {{__("Discontinued")}}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>{{__("Change application date")}}</label>
                <input type="date" name="changeApplicationDate" @error('startDate') is-invalid @enderror class="form-control" value="@if(old('changeApplicationDate')){{old('changeApplicationDate')}}@else{{$line->change_application_date}}@endif">
                @error('changeApplicationDate')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>{{__("Contract renewal date")}}</label>
                <input type="date" name="contractRenewalDate" @error('contractRenewalDate') is-invalid @enderror class="form-control" value="@if(old('contractRenewalDate')){{old('contractRenewalDate')}}@else{{$line->contract_renewal_date}}@endif">
                @error('contractRenewalDate')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>{{__("Note")}}</label>
                <input type="text" name="memo" class="form-control" value="@if(old('memo')){{old('memo')}}@else{{$line->memo}}@endif">
            </div>

            <div class="submit-buttons">
            <a class="btn btn-square" href="{{Route('admin.lineList',['id'=>$userId])}}">{{__("Cancel")}}</a>
            <input type="submit" class="btn btn-submit" value="{{__("Submit")}}">
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            var checkboxes = $('input:checkbox:checked').length;
            if(checkboxes >= 10){
                    $('input:checkbox:not(:checked)').attr("disabled", "disabled");
            }
            
            $('.optionPlan').click(function(){
                var checkboxes = $('input:checkbox:checked').length;
                if(checkboxes >= 10){
                    $('input:checkbox:not(:checked)').attr("disabled", "disabled");
                }
                else{
                    $('input:checkbox:not(:checked)').removeAttr("disabled");
                }
            });
        });
    </script>
@stop
