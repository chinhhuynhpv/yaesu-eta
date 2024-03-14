@extends('operator.body')

@section('container')
    <div class="">
        <h2 class="text-center">{{__("Line and talk group setting")}}</h2>

    @if(!empty($user->contractor_id))
        <div class="user-info">
        <div class=""><span>{{__("message.customer_code")}}: </span><span>{{$user->contractor_id}}</span></div>
        <div class=""><span>{{__("message.customer_name")}}: </span><span>{{$user->contract_name}}</span></div>
        </div>
    @endif

        <form method="post" action="{{route('operator.lineTalkGroupStore')}}" class="iu">
            @csrf
            <input type="hidden" name="id" class="form-control" value="{{$lineTalkGroup->id}}" required>
            <input type="hidden" name="userId" value='{{$lineTalkGroup->user_id}}'>
            <input type="hidden" name="shopId" value='{{$lineTalkGroup->shop_id}}'>
            <input type="hidden" name="requestId" value='{{$lineInformation->request_id}}'>
            <input type="hidden" name="currentUserId" value='{{$userId}}'>
            <div class="form-group">
                <label>{{__("Line Id")}}</label>
                <select class="form-select form-select-lg" @error('lineId') is-invalid @enderror name="lineId" required>
                    <option value="">select line ID</option>
                    @foreach($lineLists as $line)
                        <option value="{{$line->id}}"  @if($line->id == $lineTalkGroup->line_id) selected @endif> {{$line->voip_line_id}} </option>
                    @endforeach
                </select>
                @error('lineId')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>{{__("Id Name")}}</label>
                <input type="text" id='voipIdName' name="voipIdName" readonly  class="form-control" value="{{$lineInformation->voip_id_name}}" required>
            </div>
            <div class="form-group">
                <label>{{__("Line number")}}</label>
                <input type="text" id='lineNum' name="lineNum" class="form-control" readonly  value="{{$lineInformation->line_num}}" required>
            </div>

            <h3>{{__("Choose Group")}} </h3>
            <div class="form-group">
                <label>{{__("Group Owner")}}</label>
                <select class="form-select form-select-lg" @error('groupMain') is-invalid @enderror name="groupMain">
                    <option value="">Select Group</option>
                    @foreach($talkGroups as $talkGroup)
                        <option value="{{$talkGroup->id}}"  
                        @foreach( $listTalkGroups as $ltg)
                            @if($ltg->id == $talkGroup->id && $ltg->number == 1) selected @endif
                        @endforeach
                            > {{$talkGroup->name}} </option>
                    @endforeach
                </select>
                @error('groupMain')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group2">
                <label  for="">{{__("Select Group")}}</label>
                <select id="id_tag" name="selectGroup[]" class="form-control form-control-sm  js-example-basic-multiple-limit chosen-select" multiple="multiple"> 
                    @foreach($talkGroups as $talkGroup)
                        <option value="{{$talkGroup->id}}"
                            @foreach( $listTalkGroups as $ltg)
                                @if($ltg->id == $talkGroup->id && $ltg->number != 1)
                                    selected
                                @endif
                            @endforeach
                        > {{$talkGroup->name}} </option>
                    @endforeach
                </select>
            </div>
            <div class="submit-buttons">
            <a class="btn btn-square" href="{{Route('admin.lineList',['id'=>$userId])}}">{{__("Cancel")}}</a>
            <input type="submit" class="btn btn-submit" value="{{__("Submit")}}">
            </div>
        </form>
        
    </div>

    <script type="text/javascript">
        var url = "{{Route('operator.line.id')}}";
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
