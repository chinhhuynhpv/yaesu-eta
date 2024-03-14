@if ( $userRequest->line_requests->count() > 0)
    <table class="table table-borderless">
        <thead>
            <tr>
                <th scope="col">{{__("Row Num")}}</th>
                <th scope="col">{{__("Request Type")}}</th>
                <th scope="col">{{__("Line ID")}}</th>
                <th scope="col">{{__("Line number")}}</th>
                <th scope="col">{{__("Voip Id Name")}}</th>
                <th scope="col">{{__("Call method")}}</th>
                <th scope="col">{{__("Priority")}}</th>
                <th scope="col">{{__("Individual communication")}}</th>
                <th scope="col">{{__("Recording")}}</th>
                <th scope="col">{{__("GPS")}}</th>
                <!--
                <th scope="col">{{__("Remark")}}</th>
-->
            </tr>
        </thead>
        <tbody>
        @foreach($userRequest->line_requests as $lineID)
            <tr>
                <td>{{$lineID->row_num}}</td>
                <td>{{$lineID->request_type}}</td>
                <td>{{$lineID->voip_line_id}}</td>
                <td>{{$lineID->line_num}}</td>
                <td>{{$lineID->voip_id_name}}</td>
                <td>{{__($lineID->call_type)}}</td>
                <td>{{__($lineID->priority)}}</td>
                <td>{{__($lineID->individual)}}</td>
                <td>{{__($lineID->recording)}}</td>
                <td>{{$lineID->gps}}</td>
                <!--
                <td>{{$lineID->memo}}</td>
-->
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <div class="mt-3">{{__("該当の契約者は見つかりませんでした。")}}</div>
@endif
