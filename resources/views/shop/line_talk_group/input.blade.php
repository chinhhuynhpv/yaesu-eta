@extends('shop.body')

@section('container')
    @php $user = $currentUser; @endphp
    
    <div class="">

        <h2 class="text-center">{{__("Line and talk group setting")}}</h2>

    @if(!empty($user->contractor_id))
        <div class="user-info">
          <div class=""><span>{{__("message.customer_code")}}: </span><span>{{$user->contractor_id}}</span></div>
          <div class=""><span>{{__("message.customer_name")}}: </span><span>{{$user->contract_name}}</span></div>
        </div>
    @endif

            @if(empty($lineTalkGroup))
                <form method="post" action="{{route('shop.line.talk.group.store')}}" class="iu">
                    @csrf
                    @if (!empty($lineTalkGroup->id))
                        {{method_field('PUT')}}
                        <input type="hidden" name="id" class="form-control" value="{{$line->id}}" required>
                    @endif
                    <input type="hidden" name="requestId" class="form-control" value="{{$requestId}}" required>
                    <input type="hidden" name="userId" class="form-control" value="{{$userId}}" required>
                    <input type="hidden" name="shopId" value='{{$shopId}}' required>
                    <div class="form-group">
                        <label>{{__("Line Id")}}</label>
                        <select class="form-select form-select-lg" @error('lineId') is-invalid @enderror name="lineId" required>
                            <option>{{__('Choose one')}}</option>
                            @foreach($lineLists as $line)
                                <option value="{{$line->id}}"  @if($line->id == old('lineId')) selected @endif> {{$line->voip_id_name}} </option>
                            @endforeach
                        </select>
                        @error('lineId')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>{{__("Line number")}}</label>
                        <input type="text" id='lineNum' name="lineNum" readonly class="form-control" value="{{old('lineNum')}}" required>
                    </div>
                    <div class="">
                        <input type="hidden" id='requestType' name="requestType" readonly class="form-control" value="{{old('requestType')}}" required>
                    </div>
                    <div class="form-group">
                        <label>{{__("Id Name")}}</label>
                        <input type="text" id='voipIdName' name="voipIdName" readonly class="form-control" value="{{old('voipIdName')}}" required>
                    </div>
                    

                    <h3 class="mt-3 mb-3">{{__("Choose Group")}} </h3>

                    <div class="form-group">
                        <label>{{__("Group Main")}}</label>
                        <select class="form-select form-select-lg" @error('groupMain') is-invalid @enderror name="groupMain">
                            <option value=""> {{__("Select group")}}</option>
                            @foreach($listTalkGroups as $talkGroup)
                                <option value="{{$talkGroup->id}} "  @if($talkGroup->id == old('selectGroup1')) selected @endif> {{$talkGroup->name}} </option>
                            @endforeach
                        </select>
                        @error('groupMain')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group2">
                        <label for="">{{__("Select group")}}</label>
                        <select id="id_tag" name="selectGroup[]" class="form-control form-control-sm  js-example-basic-multiple-limit" multiple="multiple"> 
                            @foreach($listTalkGroups as $talkGroup)
                                <option value="{{$talkGroup->id}}"  @if($talkGroup->id == old('selectGroup1')) selected @endif> {{$talkGroup->name}} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="submit-buttons">
                        <a class="btn btn-normal btn-square" href="{{Route('shop.applicationDetail',$requestId)}}">{{__("Cancel")}}</a>
                        <input type="submit" class="btn btn-submit" value="{{__("Submit")}}">
                    </div>
                </form>
                

            @else
            <form method="post" action="{{route('shop.line.talk.group.store.update')}}" class="iu">
                @csrf
                <input type="hidden" name="id" class="form-control" value="{{$lineTalkGroup->id}}" required>
                <input type="hidden" name="userId" class="form-control" value="{{$currentUser->id}}" required>
                <input type="hidden" name="shopId" class="form-control" value="{{$lineTalkGroup->shop_id}}" required>
                <input type="hidden" name="requestId" class="form-control" value="{{$lineTalkGroup->request_id}}" required>
                <div class="form-group">
                    <label>{{__("Line Id")}}</label>
                    <select class="form-select form-select-lg" @error('lineId') is-invalid @enderror name="lineId" required>
                        <option value="">select line ID</option>
                        @foreach($lineLists as $line)
                            <option value="{{$line->id}}"  @if($line->id == $lineTalkGroup->line_id) selected @endif> {{$line->voip_id_name}} </option>
                        @endforeach
                    </select>
                    @error('lineId')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="">
                    <input type="hidden" name="requestType" class="form-control" readonly  value="@if($currentUser->request_type == 'Add') 1 @else 2 @endif">
                </div>
                <div class="form-group">
                    <label>{{__("Id Name")}}</label>
                    <input type="text" id='voipIdName' name="voipIdName" readonly  class="form-control" value="{{$lineTalkGroup->name}}" required>
                </div>
                <div class="form-group">
                    <label>{{__("Line number")}}</label>
                    <input type="text" id='lineNum' name="lineNum" class="form-control" readonly  value="{{$lineTalkGroup->line_num}}" required>
                </div>

                <h3 class="mt-3 mb-3">{{__("Choose Group")}}</h3>
                <div class="form-group">
                    <label>{{__("Group Owner")}}</label>
                    <select class="form-select form-select-lg" @error('groupMain') is-invalid @enderror name="groupMain">
                        <option value="">Select Group</option>
                        @foreach($listTalkGroups as $talkGroup)
                            <option value="{{$talkGroup->id}}"  @if($lineTalkGroup->group_id == $talkGroup->id) selected @endif> {{$talkGroup->name}} </option>
                        @endforeach
                    </select>
                    @error('groupMain')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group2">
                    <label for="">{{__("Select group")}}</label>
                    <select id="id_tag" name="selectGroup[]" class="form-control form-control-sm  js-example-basic-multiple-limit chosen-select" multiple="multiple"> 
                       @foreach($listTalkGroups as $talkGroup)
                            <option value="{{$talkGroup->id}}"  
                            @foreach($lineTalkGroupAddReq as $ltg)
                                @if($ltg->group_id == $talkGroup->id) selected @endif
                            @endforeach
                            > {{$talkGroup->name}} 
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="submit-buttons">
                    <a class="btn btn-normal btn-square" href="{{Route('shop.applicationDetail',$lineTalkGroup->request_id)}}">{{__("Cancel")}}</a>
                    <input type="submit" class="btn btn-submit" value="{{__("Submit")}}">
                </div>
            </form>
            
        @endif
    </div>

    <script type="text/javascript">
        var url = "{{Route('shop.line.id')}}";
        $("select[name='lineId']").change(function(){
            var line_id = $(this).val();
            var token = $("input[name='_token']").val();
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    line_id: line_id,
                    _token: token
                },
                success: function(data) {
                    $('#lineNum').val(data.line_num);
                    $("#voipIdName").val(data.voip_id_name);
                   
                    if(data.request_type == 'Add')
                        $("#requestType").val('1');
                    else
                        $("#requestType").val('2');
                }
            });
        });

        $( document ).ready(function() {
            $('.js-example-basic-multiple-limit').select2();
        });
    </script>
@stop
