@extends('operator.body')

@section('container')
    <div class="content-talk-group">
    @include('alert.error')
        <h2 class="text-center">{{__('Edit Talk group')}}</h2>

        @if(!empty($user->contractor_id))
        <div class="user-info">
        <div class=""><span>{{__("message.customer_code")}}: </span><span>{{$user->contractor_id}}</span></div>
        <div class=""><span>{{__("message.customer_name")}}: </span><span>{{$user->contract_name}}</span></div>
        </div>
        @endif
        
        <form method="post" action="{{route('operator.talkGroupStore')}}" class="iu">
            @csrf
            <div class="form-group">
                <input type="hidden" name="idTalkGroup" value='{{$talkGroup->id}}'>
                <input type="hidden" name="userId" value='{{$talkGroup->user_id}}'>
                <input type="hidden" name="shopId" value='{{$talkGroup->shop_id}}'>
                <input type="hidden" name="currentUserId" value='{{$userId}}'>
            </div>
            <div class="form-group">
                <label for="groupId">{{__("VOIP Group ID")}}</label>
                <input type="text" name="groupId" @error('groupId') is-invalid @enderror value="{{$talkGroup->voip_group_id}}"  @error('groupId') is-invalid @enderror class="form-control">
                @error('groupId')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="groupName">{{__("Name")}}</label>
                <input type="text" name="groupName" value="{{$talkGroup->name}}" @error('groupName') is-invalid @enderror class="form-control" Required>
                @error('groupName')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="groupPriority">{{__("Priority")}}</label>
                <select class="form-select form-select-lg" @error('groupPriority') is-invalid @enderror name="groupPriority">
                    <option value="" >{{__('Choose one')}}</option>
                    <option value="1"  @if($talkGroup->priority == __('Level 1')) selected @endif>{{__('Level 1')}}</option>
                    <option value="2"  @if( $talkGroup->priority == __('Level 2')) selected @endif>{{__('Level 2')}}</option>
                    <option value="3"  @if( $talkGroup->priority == __('Level 3')) selected @endif>{{__('Level 3')}}</option>
                    <option value="4"  @if( $talkGroup->priority == __('Level 4')) selected @endif>{{__('Level 4')}}</option>
                    <option value="5"  @if( $talkGroup->priority == __('Level 5')) selected @endif>{{__('Level 5')}}</option>
                    <option value="6"  @if( $talkGroup->priority == __('Level 6')) selected @endif>{{__('Level 6')}}</option>
                    <option value="7"  @if( $talkGroup->priority == __('Level 7')) selected @endif>{{__('Level 7')}}</option>
                    <option value="8"  @if( $talkGroup->priority == __('Level 8')) selected @endif>{{__('Level 8')}}</option>
                    <option value="9"  @if( $talkGroup->priority == __('Level 9')) selected @endif>{{__('Level 9')}}</option>
                    <option value="10" @if( $talkGroup->priority == __('Level 110')) selected @endif>{{__('Level 10')}}</option>
                </select>
                @error('groupPriority')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="memberView">{{__("Member view")}}</label>
                <div class="row">
                    <div class="radio-box">
                        <input type="radio" name="groupMemberView" value="1" {{ $talkGroup->member_view == __('On') ? 'checked' : ''}}> {{__("On")}}
                    </div>
                    <div class="radio-box">
                        <input type="radio" name="groupMemberView" value="2"  {{ $talkGroup->member_view == __('Off') ? 'checked' : ''}}> {{__("Off")}}
                    </div>
                </div>
                @error('groupMemberView')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="groupResponsiblePerson">{{__("Group Manager")}}</label>
                <input type="text" name="groupResponsiblePerson" @error('groupResponsiblePerson') is-invalid @enderror value="{{$talkGroup->group_responsible_person}}"  @error('groupResponsiblePerson') is-invalid @enderror class="form-control">
                @error('groupResponsiblePerson')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>{{__("Status")}}</label>
                <div class="row">
                    <div class="radio-box">
                        <input type="radio" name="groupStatus" value="1" {{ $talkGroup->status == __('On') ? 'checked' : ''}}> {{__("On")}}
                    </div>
                    <div class="radio-box">
                        <input type="radio" name="groupStatus" value="2"  {{ $talkGroup->status == __('Off') ? 'checked' : ''}}> {{__("Off")}}
                    </div>
                </div>
            </div>
            <div class="submit-buttons">
                <a class="btn btn-square" href="{{Route('admin.lineList',['id'=>$userId])}}">{{__("Cancel")}}</a>
                <button type="submit" class="btn btn-submit">{{__("Submit")}}</button>
            </div>
        </form>
        
    </div>
@stop
