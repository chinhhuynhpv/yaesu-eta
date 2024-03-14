@if ( $userRequest->line_requests->count() > 0)
    <table class="list" style="font-size: 8px">
        <thead>
            <tr>
                <th scope="col">{{__("Line number")}}</th>
                <th scope="col">{{__("Id name")}}</th>
                <th scope="col">{{__("Call method")}}</th>
                <th scope="col">{{__("Priority")}}</th>
                <th scope="col">{{__("Individual_c")}}<br />{{__("I_communication")}}</th>
                <th scope="col">{{__("Recording")}}</th>
                <th scope="col">{{__("GPS")}}</th>
                <th scope="col">{{__("Commander")}}</th>
                <th scope="col">{{__("Individual priority")}}</th>
                <th scope="col">{{__("CUE_r")}}<br />{{__("C_reception")}}</th>
                <th scope="col">{{__("Video")}}</th>
                <th scope="col">{{__("Start date")}}</th>
                <th scope="col">{{__("Remark")}}</th>
            </tr>
        </thead>
        <tbody>
        @foreach($userRequest->line_requests as $key => $lineID)
            <tr>
                <td>{{substr($lineID->line_num, -4)}}</td>
                <td>{{$lineID->voip_id_name}}</td>
                <td>{{__($lineID->call_type)}}</td>
                <td>{{__($lineID->priority)}}</td>
                <td>
                    <input type="checkbox" @if($lineID->individual == __('on')) checked @endif >
                </td>
                <td>
                    <input type="checkbox" @if($lineID->recording == __('on')) checked @endif >
                </td>
                <td>
                    <input type="checkbox" @if($lineID->gps == __('on')) checked @endif >
                </td>
                <td>
                    <input type="checkbox" @if($lineID->commander == __('on')) checked @endif >
                </td>
                <td>
                    <input type="checkbox" @if($lineID->individual_priority == __('on')) checked @endif >
                </td>
                <td>
                    <input type="checkbox" @if($lineID->cue_reception == __('on')) checked @endif >
                </td>
                <td>
                    <input type="checkbox" @if($lineID->video == 'on') checked @endif >
                </td> 
                <td>{{$lineID->start_date}}</td>
                <td class="longtext">{{$lineID->memo}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <div class="mt-3">{{__("No line is requested")}}</div>
@endif
