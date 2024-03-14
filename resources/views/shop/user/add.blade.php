@extends('shop.body')

@section('container')
    <div class="content-talk-group">
        @if(empty($talkGroup))
            @include('alert.error')
            <h2 class="text-center">{{__('Add New Talk group')}}</h2>

            @if(!empty($user->contractor_id))
            <div class="user-info">
            <div class=""><span>{{__("message.customer_code")}}: </span><span>{{$user->contractor_id}}</span></div>
            <div class=""><span>{{__("message.customer_name")}}: </span><span>{{$user->contract_name}}</span></div>
            </div>
            @endif

            <form method="post" action="{{route('shop.talk.group.store')}}" class="iu">
                @csrf
                <div class="form-group">
                    <input type="hidden" name="requestId" value='{{$requestId}}'>
                    <input type="hidden" name="userId" value='{{$userId}}'>
                    <input type="hidden" name="shopId" value='{{$shopId}}'>
                    <input type="hidden" name="requestType" value='1'>
                </div>
                <div class="form-group">
                    <label>{{__("Row Num")}}<span class="asta">*</span></label>
                    <input type="number" name="rowNum" @error('rowNum') is-invalid @enderror class="form-control" value="{{old('rowNum')}}">
                    @error('rowNum')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="shopCode">{{__("Group Name")}}<span class="asta">*</span></label>
                    <input type="text" name="groupName" @error('groupName') is-invalid @enderror class="form-control" value="{{old('groupName')}}" Required>
                    @error('groupName')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="shopCode">{{__("Priority")}}<span class="asta">*</span></label>
                    <select class="" name="groupPriority">
                        <option value="" >{{__("Choose one")}}</option>
                        <option value="1"  @if(old('groupPriority') == '1') selected @endif> {{__("Level 1")}} </option>
                        <option value="2"  @if( old('groupPriority') == '2') selected @endif> {{__("Level 2")}} </option>
                        <option value="3"  @if( old('groupPriority') == '3') selected @endif> {{__("Level 3")}} </option>
                        <option value="4"  @if( old('groupPriority') == '4') selected @endif> {{__("Level 4")}} </option>
                        <option value="5"  @if( old('groupPriority') == '5') selected @endif> {{__("Level 5")}} </option>
                        <option value="6"  @if( old('groupPriority') == '6') selected @endif> {{__("Level 6")}} </option>
                        <option value="7"  @if( old('groupPriority') == '7') selected @endif> {{__("Level 7")}} </option>
                        <option value="8"  @if( old('groupPriority') == '8') selected @endif> {{__("Level 8")}} </option>
                        <option value="9"  @if( old('groupPriority') == '9') selected @endif> {{__("Level 9")}} </option>
                        <option value="10" @if( old('groupPriority') == '10') selected @endif> {{__("Level 10")}} </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="shopCode">{{__("Member view")}}<span class="asta">*</span></label>
                    <div class="row">
                        <div class="radio-box">
                            <input type="radio" name="groupMemberView" value="1" {{ old('groupMemberView') == '1' ? 'checked' : ''}}> {{__("Off")}}
                        </div>
                        <div class="radio-box">
                            <input type="radio" name="groupMemberView" value="2"  {{ old('groupMemberView') == '2' ? 'checked' : ''}}> {{__("On")}}
                        </div>
                    </div>
                    @error('groupMemberView')
                                <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="shopCode">{{__("Group Responsible Person")}}<span class="asta">*</span></label>
                    <input type="text" name="groupResponsiblePerson" @error('groupResponsiblePerson') is-invalid @enderror class="form-control" value="{{old('groupResponsiblePerson')}}">
                    @error('groupResponsiblePerson')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="submit-buttons">
                    <a href="{{Route('shop.applicationDetail', $requestId)}}" class="btn btn-normal btn-square">{{__("Cancel")}}</a>
                    <button type="submit" class="btn btn-submit">{{__("Submit")}}</button>
               </div>
            </form>
            

        @else
            <h2 class="text-center">{{__("Edit Talk group")}}</h2>

            @if(!empty($user->contractor_id))
            <div class="user-info">
            <div class=""><span>{{__("message.customer_code")}}: </span><span>{{$user->contractor_id}}</span></div>
            <div class=""><span>{{__("message.customer_name")}}: </span><span>{{$user->contract_name}}</span></div>
            </div>
            @endif

            <form method="post" action="{{route('shop.talk.group.store')}}" class="iu">
                @csrf
                <div class="form-group">
                    <input type="hidden" name="idTalkGroup" value='{{$idTalkGroup}}'>
                    <input type="hidden" name="requestId" value='{{$talkGroup->request_id}}'>
                    <input type="hidden" name="userId" value='{{$talkGroup->user_id}}'>
                    <input type="hidden" name="shopId" value='{{$talkGroup->shop_id}}'>
                    <input type="hidden" name="requestType" value='{{ $talkGroup->request_type == 'Add' ? '1' : '2'}}'>
                </div>
                <div class="form-group">
                    <label for="shopCode">{{__("Row Num")}}<span class="asta">*</span></label>
                    <input type="number" name="rowNum" value="@if(empty(old('rowNum'))){{$talkGroup->row_num}}@else{{old('rowNum')}}@endif" @error('rowNum') is-invalid @enderror class="form-control" >
                    @error('rowNum')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="shopCode">{{__("Name")}}<span class="asta">*</span></label>
                    <input type="text" name="groupName" value="@if(empty(old('groupName'))){{$talkGroup->name}}@else{{old('groupName')}}@endif" @error('groupName') is-invalid @enderror class="form-control" Required>
                    @error('groupName')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="shopCode">{{__("Priority")}}<span class="asta">*</span></label>
                    <select class="form-select form-select-lg" name="groupPriority">
                        <option value="1"  @if($talkGroup->priority == __('Level 1')) selected @endif> {{__("Level 1")}} </option>
                        <option value="2"  @if( $talkGroup->priority == __('Level 2')) selected @endif> {{__("Level 2")}} </option>
                        <option value="3"  @if( $talkGroup->priority == __('Level 3')) selected @endif> {{__("Level 3")}}</option>
                        <option value="4"  @if( $talkGroup->priority == __('Level 4')) selected @endif> {{__("Level 4")}}</option>
                        <option value="5"  @if( $talkGroup->priority == __('Level 5')) selected @endif> {{__("Level 5")}}</option>
                        <option value="6"  @if( $talkGroup->priority == __('Level 6')) selected @endif> {{__("Level 6")}}</option>
                        <option value="7"  @if( $talkGroup->priority == __('Level 7')) selected @endif> {{__("Level 7")}}</option>
                        <option value="8"  @if( $talkGroup->priority == __('Level 8')) selected @endif> {{__("Level 8")}}</option>
                        <option value="9"  @if( $talkGroup->priority == __('Level 9')) selected @endif> {{__("Level 9")}}</option>
                        <option value="10" @if( $talkGroup->priority == __('Level 110')) selected @endif> {{__("Level 10")}}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="memberView">{{__("Member view")}}<span class="asta">*</span></label>
                    <div class="row">
                        <div class="radio-box">
                            <input type="radio" name="groupMemberView" value="1"  {{ $talkGroup->member_view == __('on') ? 'checked' : ''}}> {{__("On")}}
                        </div>
                        <div class="radio-box">
                            <input type="radio" name="groupMemberView" value="2" {{ $talkGroup->member_view == __('off') ? 'checked' : ''}}> {{__("Off")}}
                        </div>
                    </div>
                    @error('groupMemberView')
                                <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="groupResponsiblePerson">{{__("Group Responsible Person")}}<span class="asta">*</span></label>
                    <input type="text" name="groupResponsiblePerson" value="@if(empty(old('groupResponsiblePerson'))){{$talkGroup->group_responsible_person}}@else{{old('groupResponsiblePerson')}}@endif"  @error('groupResponsiblePerson') is-invalid @enderror class="form-control">
                    @error('groupResponsiblePerson')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="submit-buttons">
                  <a class="btn btn-normal btn-square" href="{{Route('shop.applicationDetail', $talkGroup->request_id)}}">{{__("Cancel")}}</a>
                  <button type="submit" class="btn btn-submit">{{__("Submit")}}</button>
                </div>
            </form>
            
        @endif
    </div>
@stop
