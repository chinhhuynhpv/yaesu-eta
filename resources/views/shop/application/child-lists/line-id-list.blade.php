

    <table class="table table-borderless" data-delete-action="{{route('shop.line.delete')}}">
        <thead>
        <tr>
            <th scope="col">{{__("Row Num")}}</th>
            <th scope="col">{{__("Request Type")}}</th>
            <th scope="col">{{__("Voip Line Id")}}</th>
            <th scope="col">{{__("Line number")}}</th>
            <th scope="col">{{__("Id name")}}</th>
            <th scope="col">{{__("Call method")}}</th>
            <th scope="col">{{__("Priority")}}</th>
            <!--
            <th scope="col">{{__("Individual communication")}}</th>
            <th scope="col">{{__("Recording")}}</th>
            <th scope="col">{{__("GPS")}}</th>

            <th scope="col">{{__("Remark")}}</th>
-->
        <th scope="col">{{__("Action")}}</th>
    </tr>
    </thead>

    @if ( $userRequest->line_requests->count() > 0)
        <tbody>
        @foreach($userRequest->line_requests as $lineID)
            <tr>
                <td>{{$lineID->row_num}}</td>
                <td>{{__($lineID->request_type)}}</td>
                <td>{{$lineID->voip_line_id}}</td>
                <td>{{$lineID->line_num}}</td>
                <td>{{$lineID->voip_id_name}}</td>
                <td>{{__($lineID->call_type)}}</td>
                <td>{{__($lineID->priority)}}</td>
<!--
                <td>{{$lineID->individual}}</td>
                <td>{{$lineID->recording}}</td>
                <td>{{$lineID->gps}}</td>

                <td>{{$lineID->memo}}</td>
-->
                <td>
                    @if ($status != '2')
                        <a class="btn btn-update"
                           href="{{route("shop.line.update", ['id' => $lineID->id])}}">{{__("Update")}}</a>
                        <a class="btn btn-delete" data-id="{{$lineID->id}}" data-name="{{$lineID->name}}"
                           data-delete>{{__("Delete")}}</a>
                    @else
                        <button class="btn btn-update" disabled>{{__("Update")}}</button>
                        <button class="btn btn-delete" disabled>{{__("Delete")}}</button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    @endif
</table>

<div class="mt-3 rht">
    @if ($status != '2')
        <a class="btn btn-add mr-2"
           href="{{Route('shop.line.add', ['user_id' => $userRequest->user_id, 'request_id' => $userRequest->id])}}">{{__("Add New")}}</a>
        <a class="btn btn-exist"
           href="{{route("shop.existedList", ['request_id' => $userRequest->id])}}">{{__("Add Exist")}}</a>
    @else
        <button class="btn btn-add mr-2" disabled>{{__("Add New")}}</button>
        <button class="btn btn-exist" disabled>{{__("Add Exist")}}</button>
    @endif
</div>
