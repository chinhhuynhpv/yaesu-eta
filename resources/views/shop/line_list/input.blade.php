@extends('shop.body')

@section('container')
    <div class="">

        <div class=""><h2>
            @if(empty($line))
                {{__("Add Line List")}}
            @else
                {{__("Edit Line List")}}
            @endif
        </h2></div>

        @if(!empty($user->contractor_id))
        <div class="user-info">
            <div class=""><span>{{__("message.customer_code")}}: </span><span>{{$user->contractor_id}}</span></div>
            <div class=""><span>{{__("message.customer_name")}}: </span><span>{{$user->contract_name}}</span></div>
        </div>
        @endif

        @if(empty($line))
            <form method="post" action="{{route('shop.line.store')}}" class="iu">
                @csrf
                @if (!empty($line->id))
                    {{method_field('PUT')}}
                    <input type="hidden" name="id" class="form-control" value="{{$line->id}}" required>
                @endif
                <input type="hidden" name="requestId" class="form-control" value="{{$requestId}}">
                <input type="hidden" name="userId" class="form-control" value="{{$userId}}">
                <input type="hidden" name="shopId" value='{{$currentUser->id}}'>
                <input type="hidden" name="requestType" value='1'>
                <div class="form-group">
                    <label>{{__("Contractor Id")}}</label>
                    <input type="text" name="contractor_id" @error('contractor_id') is-invalid @enderror readonly class="form-control" value="{{$user->contractor_id}}" >
                    @error('contractor_id')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>{{__("Row Num")}}<span class="asta">*</span></label>
                    <input type="number" name="rowNum" @error('rowNum') is-invalid @enderror class="form-control" value="{{old('rowNum')}}">
                    @error('rowNum')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>{{__("Seri number")}}<span class="asta">*</span></label>
                    <input type="number" name="seri_number" @error('seri_number') is-invalid @enderror class="form-control" value="{{old('seri_number')}}" >
                    @error('seri_number')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>{{__("Voip Id Name")}}<span class="asta">*</span></label>
                    <input type="text" name="voipIdName" @error('voidIdName') is-invalid @enderror class="form-control" value="{{old('voipIdName')}}" required>
                    @error('voipIdName')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>{{__("Request Type")}}</label>
                    <select class="form-select form-select-lg mt-3" name="requestType">
                        <option value="1" @if(old('requestType') == '1') selected @endif> {{__('Add')}} </option>
                        <option value="2" @if(old('requestType') == '2') selected @endif> {{__('Modify')}} </option>
                        <option value="3" @if(old('requestType') == '3') selected @endif> {{__('Pause')}} </option>
                        <option value="4" @if(old('requestType') == '4') selected @endif> {{__('Restart')}} </option>
                        <option value="5" @if(old('requestType') == '5') selected @endif> {{__('Discontinued')}} </option>
                    </select>
                </div>
                <div class="form-group">
                    <label>{{__("Usage plan")}}<span class="asta">*</span></label>
                    <select class="form-select form-select-lg" name="usagePlan">
                        @foreach($usagePlans as $usagePlan)
                            <option value="{{$usagePlan->id}}"  @if($usagePlan->id == old('usagePlan')) selected @endif>{{$usagePlan->plan_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>{{__("Start Date")}}<span class="asta">*</span></label>
                    <input type="date" name="startDateOfPlan" @error('startDateOfPlan') is-invalid @enderror value="{{old('startDateOfPlan')}}">
                    @error('startDateOfPlan')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>{{__("Automatic Update")}}<span class="asta">*</span></label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="2" name="automaticUpdate" @if(old('automaticUpdate') == '2') checked @elseif(old('automaticUpdate') == '') checked @endif>
                        <label class="form-check-label" for="flexRadioDefault2">
                        {{__("Yes")}}    
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="1" name="automaticUpdate" @if(old('automaticUpdate') == '1') checked @endif>
                        <label class="form-check-label" for="flexRadioDefault1">
                        {{__("No")}}
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{__("Option plan")}}</label>
                </div>
                <table class="table-combo table" id="table-combo-retail">
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
                                <input type="checkbox" class="optionPlan" value="{{$optionPlan->id}}" name="optionPlan[]" 
                                @if(($optionPlan->id == old('optionPlan'))
                                ) checked @endif> 
                                <span></span>
                                </label>
                            </td>
                            <td class="lbl-strong">
                                {{__($optionPlan->option_type)}}
                            </td>
                            <td class="lbl-strong">
                                {{$optionPlan->plan_name}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="form-group">
                    <label>{{__("VOIP Line password")}}<span class="asta">*</span></label>
                    <input type="text" name="voipLinePassword"  @error('voipLinePassword') is-invalid @enderror class="form-control" value="{{old('voipLinePassword')}}">
                    @error('voipLinePassword')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>{{__("Call method")}}<span class="asta">*</span></label>
                    <div class="row">
                        <div class="radio-box">
                            <input type="radio" name="callType" value="1" @if(old('callType') == '1') checked @elseif(old('callType') == '') checked @endif> {{__("Simple")}}
                        </div>
                        <div class="radio-box">
                            <input type="radio" name="callType" value="2"  @if(old('callType') == '2') checked @endif> {{__("Full duflex")}}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{__("priority")}}<span class="asta">*</span></label>
                    <select class="form-select form-select-lg" name="priority" required>
                        <option value="1"  @if( old('priority') == '1') selected @endif> {{__("Level 1")}} </option>
                        <option value="2"  @if( old('priority') == '2') selected @endif> {{__("Level 2")}} </option>
                        <option value="3"  @if( old('priority') == '3') selected @endif> {{__("Level 3")}} </option>
                        <option value="4"  @if( old('priority') == '4') selected @endif> {{__("Level 4")}} </option>
                        <option value="5"  @if( old('priority') == '5') selected @endif> {{__("Level 5")}} </option>
                        <option value="6"  @if( old('priority') == '6') selected @endif> {{__("Level 6")}} </option>
                        <option value="7"  @if( old('priority') == '7') selected @endif> {{__("Level 7")}} </option>
                        <option value="8"  @if( old('priority') == '8') selected @endif> {{__("Level 8")}} </option>
                        <option value="9"  @if( old('priority') == '9') selected @endif> {{__("Level 9")}} </option>
                        <option value="10" @if( old('priority') == '10') selected @endif> {{__("Level 10")}} </option>
                    </select>
                </div>
                <div class="form-group">
                    <label>{{__("Individual communication")}}<span class="asta">*</span></label>
                    <div class="row">
                        <div class="radio-box">
                            <input type="radio" name="individual" value="2" @if(old('individual') != '1') checked @elseif(old('individual') == '') checked @endif> {{__("Off")}}
                        </div>
                        <div class="radio-box">
                            <input type="radio" name="individual" value="1" @if(old('individual') == '1') checked @endif> {{__("On")}}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{__("Recording")}}</label>
                    <div class="row">
                        <div class="radio-box">
                            <input type="radio" name="recording" value="2" @if(old('recording') != '1') checked @elseif(old('recording') == '') checked @endif> {{__("Off")}}
                        </div>
                        <div class="radio-box">
                            <input type="radio" name="recording" value="1" @if(old('recording') == '1') checked @endif> {{__("On")}}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{__("GPS")}}<span class="asta">*</span></label>
                    <div class="row">
                        <div class="radio-box">
                            <input type="radio" name="gps" value="2" @if(old('gps') != '1') checked @elseif(old('gps') == '') checked @endif> {{__("Off")}}
                        </div>
                        <div class="radio-box">
                            <input type="radio" name="gps" value="1" @if(old('gps') == '1') checked @endif> {{__("On")}}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{__("Commander")}}<span class="asta">*</span></label>
                    <div class="row">
                        <div class="radio-box">
                            <input type="radio" name="commander" value="2" @if(old('commander') != '1') checked @elseif(old('commander') == '') checked @endif> {{__("Off")}}
                        </div>
                        <div class="radio-box">
                            <input type="radio" name="commander" value="1" @if(old('commander') == '1') checked @endif> {{__("On")}}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{__("Individual priority")}}<span class="asta">*</span></label>
                    <div class="row">
                        <div class="radio-box">
                            <input type="radio" name="individualPriority" value="2" @if(old('individualPriority') != '1') checked @elseif(old('individualPriority') == '') checked @endif> {{__("Off")}}
                        </div>
                        <div class="radio-box">
                            <input type="radio" name="individualPriority" value="1" @if(old('individualPriority') == '1') checked @endif> {{__("On")}}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{__("CUE reception")}}<span class="asta">*</span></label>
                    <div class="row">
                        <div class="radio-box">
                            <input type="radio" name="cueReception" value="2" @if(old('cueReception') != '1') checked @elseif(old('cueReception') == '') checked @endif> {{__("Off")}}
                        </div>
                        <div class="radio-box">
                            <input type="radio" name="cueReception" value="1" @if(old('cueReception') == '1') checked @endif> {{__("On")}}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{__("Video")}}<span class="asta">*</span></label>
                    <div class="row">
                        <div class="radio-box">
                            <input type="radio" name="video" value="2" @if(old('video') != '1') checked @elseif(old('video') == '') checked @endif> {{__("Off")}}
                        </div>
                        <div class="radio-box">
                            <input type="radio" name="video" value="1" @if(old('video') == '1') checked @endif> {{__("On")}}
                        </div>
                    </div>
                </div>
                <div class="submit-buttons">
                    <a class="btn btn-nomal btn-square" href="{{Route('shop.applicationDetail', $requestId)}}">{{__("Go Back")}}</a>
                    <input type="submit" class="btn btn-submit" value="{{__("Submit")}}">
                </div>
            </form>
        @else
        <form method="post" action="{{route('shop.line.store')}}" class="iu">
                @csrf
                <input type="hidden" name="id" class="form-control" value="{{$line->id}}" required>
                <div class="form-group">
                    <input type="hidden" name="requestId" class="form-control" value="{{$line->request_id}}" required>
                </div>
                <input type="hidden" name="shopId" value='{{$currentUser->id}}'>
                <div class="form-group">
                    <input type="hidden" name="userId" class="form-control" value="{{$line->user_id}}" required>
                </div>
                <div class="form-group">
                    <label>{{__("Contractor Id")}}</label>
                    <input type="text" name="contractor_id" @error('contractor_id') is-invalid @enderror readonly class="form-control" value="{{$user->contractor_id}}" required>
                    @error('contractor_id')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>{{__("Row Num")}}<span class="asta">*</span></label>
                    <input type="number" name="rowNum" @error('rowNum') is-invalid @enderror class="form-control" value="{{$line->row_num}}" >
                    @error('rowNum')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>{{__("Seri number")}}<span class="asta">*</span></label>
                    <input type="number" name="seri_number" @error('seri_number') is-invalid @enderror class="form-control" value="{{substr($line->line_num, -4);}}" >
                    @error('seri_number')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>{{__("Line number")}}<span class="asta">*</span></label>
                    <input type="number" name="line_num" @error('line_num') is-invalid @enderror readonly class="form-control" value="{{$line->line_num}}">
                    @error('lineNum')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>{{__("Request Type")}}<span class="asta">*</span></label>
                    <select class="form-select form-select-lg" name="requestType">                    
                    @if($line->request_type == 'Add')
                        <option value="1" @if($line->request_type == __('Add')) selected @endif> {{__("Add")}} </option>
                    @else
                        <option value=""> {{__("Choose one")}} </option>
                        <option value="3" @if($line->request_type == __('Pause')) selected @endif> {{__("Pause")}} </option>
                        <option value="4" @if($line->request_type == __('Restart')) selected @endif> {{__("Restart")}} </option>
                        <option value="5" @if($line->request_type == __('Discontinued')) selected @endif> {{__("Discontinued")}} </option>
                    @endif
                    </select>
                </div>
                <div class="form-group">
                    <label>{{__("Voip Id Name")}}<span class="asta">*</span></label>
                    <input type="text" name="voipIdName" @error('voipIdName') is-invalid @enderror class="form-control" value="{{$line->voip_id_name}}" required>
                    @error('voipIdName')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>{{__("Request Type")}}</label>
                    <select class="form-select form-select-lg mt-3" name="requestType">
                        <option value="1" @if($line->request_type == __('Add')) selected @endif > {{__('Add')}} </option>
                        <option value="2" @if($line->request_type == __('Modify')) selected @endif> {{__('Modify')}} </option>
                        <option value="3" @if($line->request_type == __('Pause')) selected @endif> {{__('Pause')}} </option>
                        <option value="4" @if($line->request_type == __('Restart')) selected @endif>{{__('Restart')}} </option>
                        <option value="5" @if($line->request_type == __('Discontinued')) selected @endif> {{__('Discontinued')}} </option>
                    </select>
                </div>
                <div class="form-group">
                    <label>{{__("Usage plan")}}<span class="asta">*</span></label>
                    <select class="form-select form-select-lg" name="usagePlan">
                        @foreach($usagePlans as $usagePlan)
                            <option value="{{$usagePlan->id}}"  @if(!empty($tLinePlan) && $usagePlan->id == $tLinePlan->plan_id) selected @endif>{{$usagePlan->plan_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>{{__("Start Date")}}<span class="asta">*</span></label>
                    <input type="date" name="startDateOfPlan" @error('startDateOfPlan') is-invalid @enderror value="@if(!empty($tLinePlan)){{$tLinePlan->start_date}}@endif">
                    @error('startDateOfPlan')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>{{__("Automatic Update")}}<span class="asta">*</span></label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="2" name="automaticUpdate" @if(!empty($tLinePlan) && $tLinePlan->automatic_update == __('yes')) checked @endif>
                        <label class="form-check-label" for="flexRadioDefault2">
                        {{__("Yes")}}
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="1" name="automaticUpdate" @if(!empty($tLinePlan) && $tLinePlan->automatic_update == __('no')) checked @endif>
                        <label class="form-check-label" for="flexRadioDefault1">
                            {{__("No")}}
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
                            <th scope="col" style="text-align:center">{{__("Name")}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($optionPlans as $optionPlan)
                        <tr class="col">
                            <td>
                                <label class="custome-checkbox">
                                <input type="checkbox" value="{{$optionPlan->id}}" name="optionPlan[]" class="optionPlan" 
                                @if (!empty($tLinePlan))
                                    @if(($optionPlan->id == $tLinePlan->option_id1)
                                        || ( $optionPlan->id == $tLinePlan->option_id2)
                                        || ( $optionPlan->id == $tLinePlan->option_id3)
                                        || ( $optionPlan->id == $tLinePlan->option_id4)
                                        || ( $optionPlan->id == $tLinePlan->option_id5)
                                        || ( $optionPlan->id == $tLinePlan->option_id6)
                                        || ( $optionPlan->id == $tLinePlan->option_id7)
                                        || ( $optionPlan->id == $tLinePlan->option_id8)
                                        || ( $optionPlan->id == $tLinePlan->option_id9)
                                        || ( $optionPlan->id == $tLinePlan->option_id10)
                                    ) checked @endif> 
                                @endif
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
                    <label>{{__("Voip Line password")}}<span class="asta">*</span></label>
                    <input type="text" name="voipLinePassword" @error('voipLinePassword') is-invalid @enderror class="form-control" value="{{$line->voip_line_password}}">
                    @error('voipLinePassword')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label>{{__("Call method")}}<span class="asta">*</span></label>
                    <div class="row">
                        <div class="radio-box">
                            <input type="radio" name="callType" value="1" {{ $line->call_type == __('Simple') ? 'checked' : ''}}> {{__("Simple")}}
                        </div>
                        <div class="radio-box">
                            <input type="radio" name="callType" value="2"  {{$line->call_type == __('Full duflex') ? 'checked' : ''}}> {{__("Full duflex")}}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{__("priority")}}<span class="asta">*</span></label>
                    <select class="form-select form-select-lg" name="priority" required>
                        <option value="" @if($line->priority == '') selected @endif> {{__("Choose one")}} </option>
                        <option value="1"  @if($line->priority == __('Level 1')) selected @endif> {{__("Level 1")}} </option>
                        <option value="2"  @if( $line->priority == __('Level 2')) selected @endif> {{__("Level 2")}}  </option>
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
                    <label>{{__("Individual communication")}}<span class="asta">*</span></label>
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
                    <label>{{__("Recording")}}<span class="asta">*</span></label>
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
                    <label>{{__("GPS")}}<span class="asta">*</span></label>
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
                    <label>{{__("Commander")}}<span class="asta">*</span></label>
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
                    <label>{{__("Individual priority")}}<span class="asta">*</span></label> 
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
                    <label>{{__("CUE reception")}}<span class="asta">*</span></label>
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
                    <label>{{__("Video")}}<span class="asta">*</span></label>
                    <div class="row">
                        <div class="radio-box">
                            <input type="radio" name="video" value="2"  {{$line->video == __('off') ? 'checked' : ''}}> {{__("Off")}}
                        </div>
                        <div class="radio-box">
                            <input type="radio" name="video" value="1" {{ $line->video == __('on') ? 'checked' : ''}}> {{__("On")}}
                        </div>
                    </div>
                </div>
                <div class="submit-buttons">
                    <a class="btn btn-normal btn-square" href="{{Route('shop.applicationDetail', $line->request_id)}}">{{__("Cancel")}}</a>
                    <input type="submit" class="btn btn-submit" value="{{__("Submit")}}">
                </div>
            </form>
            
        @endif
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
